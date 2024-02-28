<?php
require "settings/init.php";

date_default_timezone_set("Europe/Copenhagen");

if(empty($_GET["meroId"])){
    exit("URL'en mangler et meroId: <a href='index.php?meroId=1'>index.php?meroId=1</a>");
}

$now = date("Y-m-d H:i:s");


/*
 * Møderum info
 * */
$sql = "SELECT * FROM meetingrooms WHERE meroId = :meroId";
$bind = [":meroId" => $_GET["meroId"]];
$meetingRoom = $db->sql($sql, $bind);

if(empty($meetingRoom)){
    exit("Møderummet findes ikke");
}

$meetingRoom = $meetingRoom[0] ?? NULL;


/*
 * Aktuelt møde
 * */
$sql = "SELECT * FROM meetingrooms INNER JOIN meetings ON meroId = meetMeetingRoomsId
        WHERE meroId = :meroId
        AND meetDateFrom < :now
        AND meetDateTo > :now";
$bind = [
    ":meroId" => $_GET["meroId"],
    ":now" => $now,
];
$currentMeeting = $db->sql($sql, $bind);
$currentMeeting = $currentMeeting[0] ?? NULL;


/*
 * Fremtidige møder
 * */
$sql = "SELECT * FROM meetingrooms INNER JOIN meetings ON meroId = meetMeetingRoomsId
        WHERE meroId = :meroId
        AND meetDateFrom > :now";
$bind = [
    ":meroId" => $_GET["meroId"],
    ":now" => $now,
];
$futureMeetings = $db->sql($sql, $bind);
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    
    <title>Meeting Room App</title>
    
    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">
    
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="container-fluid">
    <div class="row vh-100">
        <div class="col-8 vh-100 position-relative bg-success" id="meetingRoom">
            <div class="row row-cols-2 fs-3 text-light border-bottom py-2">
                <div id="status">
                    <span>Optaget</span>
                    <span>Ledigt</span>
                </div>
                <div class="text-end" id="time">12:34</div>
            </div>
            
            <div class="text-light text-center py-3">
                <div class="fs-5">#<?php echo $meetingRoom->meroNumber; ?></div>
                <div class="fs-1 fw-bold"><?php echo $meetingRoom->meroName; ?></div>
            </div>
            
            <div class="text-light text-center py-3">
                <div>Skærm: <?php echo ($meetingRoom->meroScreen == 0) ? "Nej" : "Ja"; ?></div>
                <div>Maks. pers: <?php echo $meetingRoom->meroPersons; ?></div>
            </div>
            
            <div class="w-50 mx-auto text-light py-3" id="occupied">
                <div class="text-center mb-2">Optaget af:</div>
                <div class="card text-light">
                    <div class="row g-0 justify-content-center">
                        <div class="col-auto d-flex align-items-center ps-1">
                            <img src="images/uifaces5.jpg" class="profile-img rounded-circle border border-2 border-light" alt="profilbillede">
                        </div>
                        <div class="col-auto">
                            <div class="card-body">
                                <div class="card-text fw-bold">
                                    <?php echo date("H:i", strtotime($currentMeeting->meetDateFrom)) . " - " . date("H:i", strtotime($currentMeeting->meetDateTo)); ?>
                                </div>
                                <div class="card-text">Afd: <?php echo $currentMeeting->meetDepartment; ?></div>
                                <div class="card-text"><small><?php echo $currentMeeting->meetNames; ?></small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="position-absolute bottom-0 start-50 translate-middle mb-3">
                <button type="button" id="bookBtn" class="btn btn-light btn-lg px-5 rounded-5" data-bs-toggle="modal" data-bs-target="#bookRoom">Book</button>
            </div>
        
        </div>
        
        <div class="col-4 vh-100 bg-light position-fixed end-0 overflow-y-scroll">
            <div class="row row-cols-2 fs-3 border-bottom opacity-50 py-2">
                <div>Næste:</div>
                <div class="text-end" id="appDate">1. april</div>
            </div>
            
            <div class="row">
                <div id="nextUp">
                    <?php
                    foreach($futureMeetings as $futureMeeting){
                        ?>
                        <div class="card my-3">
                            <div class="row g-0">
                                <div class="col-auto d-flex align-items-center ps-1">
                                    <?php
                                    if(!empty($futureMeeting->meetImage)){
                                        ?>
                                        <img src="images/<?php echo  $futureMeeting->meetImage; ?>" class="profile-img rounded-circle border border-2 border-danger" alt="profilbillede">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <div class="card-text fw-bold">
                                            <?php
                                            echo "d. " . date("d/m", strtotime($futureMeeting->meetDateFrom)) . "<br>";
                                            echo date("H:i", strtotime($futureMeeting->meetDateFrom)) . " - " . date("H:i", strtotime($futureMeeting->meetDateTo));
                                            ?>
                                        </div>
                                        <div class="card-text">Afd: <?php echo $futureMeeting->meetDepartment; ?></div>
                                        <div class="card-text"><small class="text-body-secondary"><?php echo $futureMeeting->meetNames; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="bookRoom" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Opret booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="startTime">Fra:</label>
                    <input type="time" id="startTime" class="form-control">
                </div>
                
                <div class="mb-3">
                    <label for="endTime">Til:</label>
                    <input type="time" id="endTime" class="form-control">
                </div>
                
                <div class="mb-3">
                    <label for="selectDepartment">Vælg afdeling:</label>
                    <select id="selectDepartment" class="form-select">
                        <option value="Salgsafdeling">Salgsafdeling</option>
                        <option value="IT-afdeling">IT-afdeling</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="names">Indtast navne:</label>
                    <input type="text" id="names" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Luk</button>
                <button type="button" class="btn btn-primary" id="createBookingBtn" data-bs-dismiss="modal">Opret</button>
            </div>
        </div>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
    const meetingRoom = document.querySelector('#meetingRoom');
    const status = document.querySelector('#status');
    const occupied = document.querySelector('#occupied');
    const bookBtn = document.querySelector('#bookBtn');
    const createBookingBtn = document.querySelector('#createBookingBtn');
    const time = document.querySelector('#time');
    const appDate = document.querySelector('#appDate');
    
    createBookingBtn.addEventListener('click', () => {
        const startTime = document.querySelector('#startTime');
        const endTime = document.querySelector('#endTime');
        const selectDepartment = document.querySelector('#selectDepartment');
        const names = document.querySelector('#names');
        
        const cardBody = occupied.querySelector('.card-body');
        const cardTime = cardBody.children[0];
        const cardDepartment = cardBody.children[1];
        const cardNames = cardBody.children[2];
        
        cardTime.innerHTML = startTime.value + ' - ' + endTime.value;
        cardDepartment.innerHTML = 'Afd: ' + selectDepartment.value;
        cardNames.innerHTML = names.value;
        
        toggleStatus(true);
    })
    
    function showDate() {
        const date = new Date();
        const options = {
            day: 'numeric',
            month: 'short'
        }
        appDate.innerHTML = date.toLocaleDateString('da-DK', options);
    }
    
    showDate();
    
    function showTime() {
        const date = new Date();
        const hours = date.getHours().toString().padStart(2, 0);
        const minutes = date.getMinutes().toString().padStart(2, 0);
        const seconds = date.getSeconds().toString().padStart(2, 0);
        const clock = hours + ':' + minutes + ':' + seconds;
        time.innerHTML = clock;
        
        setTimeout(showTime, 1000);
    }
    
    showTime();
    
    function toggleStatus(isOccupied) {
        
        status.children[0].classList.remove('d-none');
        status.children[1].classList.remove('d-none');
        occupied.classList.remove('d-none');
        bookBtn.classList.remove('d-none');
        
        if(isOccupied) {
            meetingRoom.classList.remove('bg-success');
            meetingRoom.classList.add('bg-danger');
            
            status.children[0].classList.add('d-block');
            status.children[1].classList.add('d-none');
            
            occupied.classList.add('d-block');
            
            bookBtn.classList.add('d-none');
        } else {
            meetingRoom.classList.add('bg-success');
            meetingRoom.classList.remove('bg-danger');
            
            status.children[0].classList.add('d-none');
            status.children[1].classList.add('d-block');
            
            occupied.classList.add('d-none');
            
            bookBtn.classList.add('d-block');
        }
        
    }
    
    toggleStatus(<?php echo (!empty($currentMeeting)) ? "true" : "false"; ?>);

</script>
</body>
</html>
