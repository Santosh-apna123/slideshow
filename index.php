<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dynamic Image Slideshow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .carousel-inner {
    height: 500px;
    background: #000;
}

.carousel-item {
    height: 100%;
}

.carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    object_position:center;  
    background: #000;   
}


@media (max-width: 1200px) {
    .carousel-inner {
        height: 350px;  
    }
}

@media (max-width: 576px) {
    .carousel-inner {
        height: 250px; 
    }
}

  </style>
</head>

<body>
  <div id="slideshow" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators"></div>
    <div class="carousel-inner"></div>

    <button class="carousel-control-prev" type="button" data-bs-target="#slideshow" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#slideshow" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>
<div>
  <script>
    fetch('get_images.php')
      .then(response => response.json())
      .then(data => {
        const container = document.querySelector('.carousel-inner');
        const indicators = document.querySelector('.carousel-indicators');

        if (data.images.length === 0) {
          container.innerHTML = '<div class="text-center p-4">No images uploaded yet.</div>';
          return;
        }

        data.images.forEach((img, index) => {
          const div = document.createElement('div');
          div.className = 'carousel-item' + (index === 0 ? ' active' : '');
          div.innerHTML = `<img src="${img.image_url}" alt="slide">`;
          container.appendChild(div);

          const button = document.createElement('button');
          button.type = "button";
          button.setAttribute("data-bs-target", "#slideshow");
          button.setAttribute("data-bs-slide-to", index);
          if (index === 0) button.classList.add("active");
          indicators.appendChild(button);
        });
      })
      .catch(error => console.error("Error fetching images:", error));
  </script>
</body>
</html>
