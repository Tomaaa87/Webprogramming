
var modal = document.getElementById("imageModal");

var modalImg = document.getElementById("modalImage");
var captionText = document.getElementById("caption");

var thumbnails = document.querySelectorAll(".thumbnail");


thumbnails.forEach(function(thumbnail) {
    thumbnail.onclick = function() {
      
        var fullImageSrc = thumbnail.getAttribute("data-full");
        
     
        modalImg.src = fullImageSrc;
       
        captionText.innerHTML = thumbnail.alt;
        
        modal.style.display = "block";
    };
});


var closeBtn = document.getElementsByClassName("close")[0];


closeBtn.onclick = function() {
    modal.style.display = "none";
};


window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
