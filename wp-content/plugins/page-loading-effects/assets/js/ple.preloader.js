/** Page Loading Effects 1.0.2
 * @Documentation https://github.com/esstat17
 * @Copyright: InnoveDesigns.com
 * @Author: Elvin D.
 */ 
 
 
var plePreloader = {
	speed : 5000,
	elem : 'ple-loader-wraps',
	elemInner : '',
	preloaderOn : function () {
		var el = document.getElementsByTagName('html')[0],
    	// Make a new div
    	cdiv = document.createElement('div');
		cdiv.id = this.elem;
		cdiv.innerHTML = '<div id="ple-animates">'+this.elemInner+'</div>';
		el.appendChild(cdiv);
	},
	
	preloaderOff : function() {	
		
		function fadeoutFn (elem, fadespeed ) {
    		var elem = document.getElementById(elem);
			if(elem.style.display!='none'){
				document.getElementById('ple-animates').style.display='none';
				if (!elem.style.opacity) {
					elem.style.opacity = 1;
				}
				var outInterval = setInterval(function() {
					elem.style.opacity -= 0.05;
					if (elem.style.opacity <= 0) {
						clearInterval(outInterval);
            			elem.style.display='none';
					//	console.log("Preloader Off");
					} 
				}, fadespeed/50 );
			}		
		}
		var elem = this.elem,	
		fadeout = function(){
			fadeoutFn(elem, 1000);
		}
		setTimeout(fadeout, this.speed);
	},

	kicks : function() {
		this.preloaderOn();	
		this.preloaderOff();		
	}	 
}

plePreloader.speed = 5000;
plePreloader.elem = 'ple-loader';
plePreloader.elemInner = 'Loading..';
plePreloader.kicks();


	 