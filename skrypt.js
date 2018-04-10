function start() {
    if (!navigator.javaEnabled()) {
        alert('Uwaga !!! Proszę włączyć Java w przeglądarce.');
    }
}
function pum(ob, dis) {
    if (arguments.length === 1)
    {
        /*var obj = document.getElementById(ob);
        if (obj.style.display == 'none') {
            obj.style.display = 'block';
        } else {
            obj.style.display = 'none';
        }*/
        if ($(ob).css("display") === "none")
        {
            $(ob).css("display","block");
        }
        else
        {
            $(ob).css("display","none");
        }
    }
    if (arguments.length === 2)
    {
        /*var obj = document.getElementById(ob);
        if (obj.style.display == 'none') {
            obj.style.display = dis;
        } else {
            obj.style.display = 'none';
        }*/
        if ($(ob).css("display") === "none")
        {
            $(ob).css("display",dis);
        }
        else
        {
            $(ob).css("display","none");
        }
    }
}


const z = false;

function zt(ob, p)
{
    if ($(ob).children('.arrow-down').is(':hidden'))
    {
        $(ob).children('.arrow-up').hide();
        $(ob).children('.arrow-down').show();
    } else
    {
        $(ob).children('.arrow-up').show();
        $(ob).children('.arrow-up').css({display: 'inline-block'});
        $(ob).children('.arrow-down').hide();
    }
    $(ob).next(p).slideToggle(0);
}

function ps(ob, p)
{
    if ($(ob).children('.arrow-down').is(':hidden'))
    {
        $(ob).children('.arrow-up').hide();
        $(ob).children('.arrow-down').show();
    } else
    {
        $(ob).children('.arrow-up').show();
        $(ob).children('.arrow-up').css({display: 'inline-block'});
        $(ob).children('.arrow-down').hide();
    }
    $(ob).next(p).slideToggle(700, "swing");
}

function sl()
{
    // language=JQuery-CSS
    if ( $("#pfs").css("left") < "-4px") {
        $('#pfs').animate({left: '+=205px'}, 350);
        $('#produkty').animate({left: '+=210px', width: '-=210px'}, 350);
        $('html').animate({width: '-=10%'}, 350);
    } else {
        $('#pfs').animate({left: '-=205px'}, 350);
        $('#produkty').animate({left: '-=210px', width: '+=210px'}, 350);
        $('html').animate({width: '+=10%'}, 350);

    }

}
function zc()
{
    if ( $('#ar').css('height') === "0px")
    {
        $('#ar').animate({height: '100px'}, 1);
    } else {
        $('#ar').animate({height: '0px'}, 1);
    }

}

function toggleMenu() {
    if ( $('#pasek1').css("height") === "48px")
    {
        $('#pasek1').css({height: "85px"});
        $('#paseklogowania').css({height: "46px"});
        $('#paseklr').css({display: "block"});
        $('#pasekpomocy').css({display: "block"});
        $('#ikotog').attr('src','ikon/aup.png');
    }
    else
    {
        $('#pasek1').css({height: "48px"});
        $('#paseklogowania').css({height: "0px", display: "none"});
        $('#paseklr').css({display: "none"});
        $('#pasekpomocy').css({display: "none"});
        $('#ikotog').attr('src','ikon/kp.png');
    }
}

function otworztab(evt, tab) {
    // Declare all variables
    var i, tabcontent, tablinks;
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
    if (tab === 'zam') document.getElementById('lzr').click();
}

function otworztabs(evt, tab, tc) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tab).style.display = "block";
    document.getElementById(tc).style.display = "block";
    evt.currentTarget.className += " active";

}

var f = 0;

function dtab(tabn)
{
    if (f < 1)
    {
        document.getElementById(tabn).click();
        f++;
    }

}

function searchToggle(obj, evt) {
    var container = $(obj).closest('.search-wrapper');
    if (!container.hasClass('active')) {
        container.addClass('active');
        evt.preventDefault();
    } else if (container.hasClass('active') && $(obj).closest('.input-holder').length === 0) {
        container.removeClass('active');
        // clear input
        container.find('.search-input').val('');
    }
}


/* Galeria zdjęć */

var slideIndex = 1;

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" gactive", "");
    }
    slides[slideIndex - 1].style.display = "block";
    //dots[slideIndex - 1].className += " gactive";
}

var myIndex = 0;

function carousel() {
    var x = document.getElementsByClassName("mySlides");
    myIndex++;
    if (myIndex > x.length)
    {
        myIndex = 0;
    }
    plusSlides(myIndex);
    setTimeout(carousel, 8000);
}