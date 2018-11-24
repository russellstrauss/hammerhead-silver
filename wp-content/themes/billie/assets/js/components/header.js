module.exports = function() {
	
	var settings;
	
	return {
		
		settings: {
			
		},
		
		init: function() {
			
			this.animateInMobileMenu();
		},
		
		animateInMobileMenu: function() {
			
			let mainNavigation = document.querySelector('.main-navigation');
			let menu = mainNavigation.querySelector('.menu-toggle');
			
			menu.addEventListener('click', function(element) {
				
				let menuItems = document.querySelectorAll('.main-navigation .nav-menu li');
				
				console.log(element);
				
				
				for (let i = 0; i < menuItems.length; i++) {
					
					if (mainNavigation.classList.contains('toggled')) {
						
						setTimeout(function() {
							menuItems[i].style.opacity = "1";
						}, (i * 50) + 400);
					}
					else {
						menuItems[i].style.opacity = "0";
					}
				}
				
			});
		}
	}
}