window.onload=function(){
    document.getElementById("add").style.display = "inline-block";  
    document.getElementById("opener").style.display = "none";  
    document.getElementById("closer").style.display = "none";   

    function add() {
        const footer = document.getElementById('footer');
        const div = document.createElement('div');
        footer.appendChild(div);
        const para = document.createElement('p');
        div.appendChild(para);
        para.innerHTML = 'Link to the HTML validator: <a href="https://validator.w3.org">https://validator.w3.org</a> <br>Link to the CSS validator: <a href="https://jigsaw.w3.org/css-validator/">https://jigsaw.w3.org/css-validator/</a>';
        para.style.color = "white";
        div.setAttribute('id', 'links');
        div.setAttribute('title', 'validation links');
        document.getElementById("closer").style.display = "inline-block";   
        document.getElementById("add").style.display = "none";
    }

    function show() {
        document.getElementById("opener").style.display = "none";
        document.getElementById("closer").style.display = "inline-block";
        document.getElementById("links").style.display = "block";
    }
    
    function close() {
        document.getElementById("links").style.display = "none";
        document.getElementById("closer").style.display = "none";
        document.getElementById("opener").style.display = "inline-block";
    }
    
    const addButton = document.getElementById('add');
    addButton.addEventListener('click', add);

    const openButton = document.getElementById('opener');
    openButton.addEventListener('click', show);

    const closeButton = document.getElementById('closer');
    closeButton.addEventListener('click', close);

document.getElementById("frame").style.backgroundColor = "white";

    navigator.geolocation.getCurrentPosition(position => {
        console.log(position.coords);   
        var latitude = position.coords.latitude; 
        var longitude = position.coords.longitude;
        var latitudeFixed = latitude.toFixed(4);
        var longitudeFixed = longitude.toFixed(4);
        const srclink = "https://www.google.com/maps/embed/v1/directions?key=AIzaSyCQOkCNIoK0YniJ5kbClcLP2lbCs6trzM0&origin=" + latitudeFixed+ "," + longitudeFixed + "&destination=Japan&mode=walking";
        document.getElementById("frame").src = srclink;
        document.getElementById("frame").style.backgroundColor = "transparent";
    });


   


  

}



function mycarousel_initCallback(carousel) {

    carousel.buttonNext.bind('click', function () {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function () {
        carousel.startAuto(0);
    });

   
    carousel.clip.hover(function () {
        carousel.stopAuto();
    }, function () {
        carousel.startAuto();
    });
};



$(document).ready(function () {

    $(".btn-slide").click(function () {
        $("#panel").slideToggle("slow");
        $(this).toggleClass("active");
    });

    $(".delete").click(function() {
        $(this).parent().parent().animate({ opacity: "hide" }, "slow");
    });


 
    $(document).bind('contextmenu', function (e) { return false; });


    jQuery('#mycarousel').jcarousel({
        auto: 2,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });


});


function laduj(obj) {
    document.getElementById("rys").src = obj.src;
}




