<?php
$conn = new mysqli("localhost", "root", "", "website");
$conn->set_charset("utf8");

// Lấy dữ liệu slider
$sql = "SELECT * FROM slider ORDER BY sort_order ASC";
$result = $conn->query($sql);

$sliders = [];
while ($row = $result->fetch_assoc()) {
  $sliders[] = $row;
}
?>
<head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<!-- Slider HTML -->
<div class="custom-slider">
  <div class="slides" id="slides">
    <?php foreach ($sliders as $slide): ?>
      <div class="slide">
        <img src="assets/images/Slider/<?= htmlspecialchars($slide['image_link']) ?>" alt="<?= htmlspecialchars($slide['name']) ?>">
      </div>
    <?php endforeach; ?>
  </div>

<a class="prev" onclick="moveSlide(-1)">
  <i class="fas fa-chevron-left"></i>
</a>
<a class="next" onclick="moveSlide(1)">
  <i class="fas fa-chevron-right"></i>
</a>


  <div class="dots" id="dots">
    <?php for ($i = 0; $i < count($sliders); $i++): ?>
      <span class="dot" onclick="currentSlide(<?= $i ?>)"></span>
    <?php endfor; ?>
  </div>
</div>

<!-- Slider CSS -->
<style>
.custom-slider {
  position: relative;
  width: 100vw;     
  height: 100vh;    
  overflow: hidden;
  margin: 0;
}

.custom-slider {
  width: 100vw;
  height: 58vh;
  overflow: hidden;
  position: relative;
  margin: 0;
}

.slides {
  display: flex;
  transition: transform 1s ease;
  height: 100%;
}

.slide {
  min-width: 100%;
  height: 100%;
}

.slide img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}


.prev, .next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 30px;
  color: white;
  cursor: pointer;
  background: transparent;
  border: none;
  z-index: 10;
  padding: 10px;
  transition: color 0.3s;
}

.prev:hover, .next:hover {
  color: #ddd;
}

.prev { left: 20px; }
.next { right: 20px; }


.dots {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
}
.dot {
  display: inline-block;
  width: 12px;
  height: 12px;
  margin: 0 5px;
  background: #bbb;
  border-radius: 50%;
  cursor: pointer;
}
.dot.active {
  background: #fff;
}
@media (max-width: 768px) {
  .custom-slider {
    height: 40vh;
  }

  .prev, .next {
    font-size: 24px;
    padding: 6px;
  }

  .dot {
    width: 10px;
    height: 10px;
    margin: 0 3px;
  }

  .slide img {
    object-fit: cover; /* cho mobile nhìn đầy đủ hơn */
  }
}


</style>

<!-- Slider JavaScript -->
<script>
let currentIndex = 0;
let slides = document.getElementById("slides");
let dots = document.getElementsByClassName("dot");
let totalSlides = slides.children.length;

function updateSlider(index) {
  slides.style.transform = "translateX(" + (-index * 100) + "%)";
  for (let dot of dots) dot.classList.remove("active");
  dots[index].classList.add("active");
}

function moveSlide(n) {
  currentIndex = (currentIndex + n + totalSlides) % totalSlides;
  updateSlider(currentIndex);
}

function currentSlide(n) {
  currentIndex = n;
  updateSlider(currentIndex);
}

function autoSlide() {
  moveSlide(1);
  setTimeout(autoSlide, 6000);
}

window.onload = () => {
  updateSlider(currentIndex);
  autoSlide();
};
</script>
