<?php
require "settings/init.php";

$sql = "SELECT * FROM meetingrooms WHERE meroId = :meroId";
$bind = [":meroId" => $_GET["meroId"]];
$meetingRoom = $db->sql($sql, $bind);

$todayFrom = date("Y-m-d") . " 00:00:00";
$todayTo = date("Y-m-d") . " 23:59:59";
$sql = "SELECT * FROM meetingrooms INNER JOIN meetings ON meroId = meetMeetingRoomsId
        WHERE meroId = :meroId
        AND meetDateFrom > :todayFrom
        AND meetDateTo < :todayTo";
$bind = [
    ":meroId" => $_GET["meroId"],
    ":todayFrom" => $todayFrom,
    ":todayTo" => $todayTo
];
$meetings = $db->sql($sql, $bind);
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
                <div class="fs-5">#123</div>
                <div class="fs-1 fw-bold">Lokalenavn</div>
            </div>
            
            <div class="text-light text-center py-3">
                <div>Skærm: Ja</div>
                <div>Maks. pers: 20</div>
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
                                <div class="card-text fw-bold">07:15 - 08:00</div>
                                <div class="card-text">Afd: Marketingafdeling</div>
                                <div class="card-text"><small>Adam, Bent, Carl</small></div>
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
                    foreach($meetings as $meeting){
                        ?>
                        <div class="card my-3">
                            <div class="row g-0">
                                <div class="col-auto d-flex align-items-center ps-1">
                                    <img src="images/uifaces1.jpg" class="profile-img rounded-circle border border-2 border-danger" alt="profilbillede">
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <div class="card-text fw-bold">
                                            <?php echo date("H:i", strtotime($meeting->meetDateFrom)) . " - " . date("H:i", strtotime($meeting->meetDateTo)); ?>
                                        </div>
                                        <div class="card-text">Afd: <?php echo $meeting->meetDepartment; ?></div>
                                        <div class="card-text"><small class="text-body-secondary"><?php echo $meeting->meetNames; ?></small>
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
        const clock = hours + ':' + minutes;
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
    
    toggleStatus(false);

</script>
</body>
</html>
