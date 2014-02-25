function init()
{
	//Center images on carousel
	var carouselImages = $('.carousel-inner img');
	
	for(var i = 0; i < carouselImages.length; i++)
	{
		carouselImages.get(i).style.left = (window.innerWidth/2) - (carouselImages.get(i).naturalWidth/2) + 'px';
		carouselImages.get(i).style.width = carouselImages.get(i).naturalWidth;
		carouselImages.get(i).style.height = carouselImages.get(i).naturalHeight;
		carouselImages.get(i).style.visibility = 'visible';
	}
	
	window.onresize = function() 
	{
	    init();
	}
}
