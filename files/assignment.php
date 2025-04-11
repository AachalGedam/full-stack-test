<?php
include 'db.php';

try {
    $stmt = $conn->query("SELECT * FROM your_table ORDER BY id ASC");
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>CRUD Slider Interface</title>
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <!-- Optional custom styles -->
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    .image-preview {
      width: 100%;
      padding-top: 100%; /* 1:1 Aspect Ratio */
      background-size: cover;
      background-position: center;
      border: 1px solid #ccc;
    }
    .tab-button {
      cursor: pointer;
    }
    .tab-button.active {
      font-weight: bold;
      background: #e9ecef;
    }

    body{
      background-color: #11324d;      ;
    }
    .h1{
      color: white;
      font-size: 29px;
      text-align: center;
      padding-top: 4%;
    }
    .h4{
      color: white;
      font-size: 16px;
      text-align: center;
    }
    
     .main ul li:nth-child(2) span 
     {
      background-image: url('images/DL-technology.svg');
      width: 100px;
      height: 70px;
      background-repeat: no-repeat;
     }
     .main ul li:nth-child(1) span 
     {
      background-image: url('images/DL-learning.svg');
      width: 100px;
      height: 70px;
      background-repeat: no-repeat;
     }
     .main ul li:nth-child(3) span 
     {
      background-image: url('images/DL-communication.svg');
      width: 100px;
      height: 70px;
      background-repeat: no-repeat;
     }
    .bg-one{
      background-color: #f6f6f6;
    }
    .bg-64b4c8{
      background-color: #64b4c8;
    }
    .d-none{
      padding-top: 44%;
    }
    .slide-content{
      padding-top: 43%;
    }
    
    @media only screen and (min-width:0px) and (max-width:768px)
    {
      body{
        padding: 1%;
      }
     .card-body{
      display: none;
     }
     
    }
  </style>
</head>
<body>
<div>
  <div>
  <p class="h1">DelphianLogic in Action</p>
  </div>
  <div>
  <p class="h4">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo</p>
  </div><br>

<div class="container">
  <div class="row">
     
    <!-- Column 1: Tabs (Desktop) / Accordion (Mobile) -->
    <div class="col-md-3 bg-light ">
      <div class="d-none d-md-block main">
        <ul class="nav flex-column nav-pills mx-3" id="slideTabs ">
          <?php foreach ($slides as $index => $slide): ?>
            <li class="nav-item bg-white mb-3 shadow">
              
              <a class="nav-link tab-button d-flex <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>" href="#">
                <span></span><?= htmlspecialchars($slide['title']) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="d-md-none  accordion" id="mobileAccordion">
        <?php foreach ($slides as $index => $slide): ?>
          <div class="card">
            <div class="card-header" id="heading<?= $index ?>">
              <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left <?= $index === 0 ? '' : 'collapsed' ?>" data-toggle="collapse" data-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                  <?= htmlspecialchars($slide['title']) ?>
                </button>
              </h2>
            </div>
            <div id="collapse<?= $index ?>" class="collapse <?= $index === 0 ? 'show' : '' ?>" data-parent="#mobileAccordion">
              <div class="card-body">
                <?= nl2br(htmlspecialchars($slide['description'])) ?>
              </div>
         
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Column 2: Slider Content -->
    <div class="col-md-4 bg-64b4c8" id="sliderContent">
      <?php foreach ($slides as $index => $slide): ?>
        <div class="slide-content px-5 text-center fw-bolder text-white <?= $index === 0 ? '' : 'd-none' ?>" data-index="<?= $index ?>">
          <div class="text-center bg-secondary">
            <a href="" class="text-white">DIGITAL LEARNING INFRASTRUCTURE</a>
          </div><br>
          <!-- <h3><?= htmlspecialchars($slide['title']) ?></h3> -->
          <p class="font-weight-bolder"><?= nl2br(htmlspecialchars($slide['description'])) ?></p>
        </div>
        
      <?php endforeach; ?>
              <div class="text-center">
                <a href="#" class="text-white">Learn More <img class="text-white" src="images/arrow-right.svg" alt=""></a>
              </div>
      <!-- Controls -->
      <!-- <div class="mt-4">
        <button class="btn btn-primary" id="prevSlide">Previous</button>
        <button class="btn btn-primary" id="nextSlide">Next</button>
      </div> -->
    </div>

    <!-- Column 3: 1:1 Image Display -->
    <div class="col-md-5 px-0">
      <?php foreach ($slides as $index => $slide): ?>
        <div class="image-preview <?= $index === 0 ? '' : 'd-none' ?>" id="image<?= $index ?>" style="background-image: url('images/<?= htmlspecialchars($slide['image']) ?>');"></div>
      <?php endforeach; ?>
    </div>

  </div>
</div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
  let currentIndex = 0;
  const totalSlides = <?= count($slides) ?>;

  function showSlide(index) {
    $('.slide-content').addClass('d-none');
    $('.image-preview').addClass('d-none');
    $(`.slide-content[data-index="${index}"]`).removeClass('d-none');
    $(`#image${index}`).removeClass('d-none');

    $('.tab-button').removeClass('active');
    $(`.tab-button[data-index="${index}"]`).addClass('active');

    currentIndex = index;
  }

  $('#nextSlide').click(function() {
    let nextIndex = (currentIndex + 1) % totalSlides;
    showSlide(nextIndex);
  });

  $('#prevSlide').click(function() {
    let prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    showSlide(prevIndex);
  });

  $('.tab-button').click(function(e) {
    e.preventDefault();
    let index = $(this).data('index');
    showSlide(index);
  });
});
</script>

</body>
</html>
