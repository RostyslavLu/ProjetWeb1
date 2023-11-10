window.onload = function() {
    magnifyGlass();
};

function magnifyGlass() {
    const img = document.querySelector('.magnify');
    //const image = img
    img.addEventListener('mousemove', function(e) {
        e.preventDefault();
        const glass = document.querySelector('.glass');
        glass.style.display = 'block';
        glass.style.position = 'absolute';
        glass.style.width = '400px';
        glass.style.height = '400px';
        glass.style.borderRadius = '50%';
        glass.style.backgroundImage = 'url(' + img.src + ')';
        
        glass.style.backgroundRepeat = 'no-repeat';
        glass.style.backgroundOrigin = '0 0';
        glass.style.backgroundSize = '650px 650px';
        glass.style.backgroundPosition = '0 0';

        glass.style.boxShadow = '0 0 0 7px rgba(0,0,0,0.2)';
        glass.style.cursor = 'none';
        glass.style.left = e.pageX - 25 + 'px';
        glass.style.top = e.pageY - 25 + 'px';
        glass.style.transform = 'translate(30%, -30%)';

        const x = e.pageX - this.offsetLeft;
        const y = e.pageY - this.offsetTop;

        
        glass.style.backgroundPositionX = -x + 'px';
        glass.style.backgroundPositionY = -y + 'px';


    });
    img.addEventListener('mouseleave', function() {
        const glass = document.querySelector('.glass');

        glass.style.display = 'none';
    });

}