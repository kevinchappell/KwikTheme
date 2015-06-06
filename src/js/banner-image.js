/*
 * Copyright (C) 2015 Kevin Chappell
 * Licensed under the MIT License
 */

(function($) {
	$.fn.bannerImage = function(settings) {
		settings = $.extend({}, settings);

		return $(this).each(function() {

			var content = $('.banner_inner', $(this)),
				blur = $('.banner_effect', $(this)),
				wHeight = $(window).height();

			$(window).on('resize', function() {
				wHeight = $(window).height();
			});

			window.requestAnimFrame = (function() {
				return window.requestAnimationFrame ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame ||
					function(callback) {
						window.setTimeout(callback, 1000 / 60);
					};
			})();

			/**
			 * Scroller
			 */
			function Scroller() {
				this.latestKnownScrollY = 0;
				this.ticking = false;
			}

			Scroller.prototype = {
				init: function() {
					window.addEventListener('scroll', this.onScroll.bind(this), false);
				},
				onScroll: function() {
					this.latestKnownScrollY = window.scrollY;
					this.requestTick();
				},
				requestTick: function() {
					if (!this.ticking) {
						window.requestAnimFrame(this.update.bind(this));
					}
					this.ticking = true;
				},
				update: function() {
					var currentScrollY = this.latestKnownScrollY;
					this.ticking = false;

					var slowScroll = currentScrollY / 6,
						blurScroll = currentScrollY * 2.5;

					content.css({
						'transform': 'translateY(-' + slowScroll + 'px)',
						'-moz-transform': 'translateY(-' + slowScroll + 'px)',
						'-webkit-transform': 'translateY(-' + slowScroll + 'px)'
					});

					blur.css({
						'opacity': blurScroll / wHeight
					});
				}
			};
			var scroller = new Scroller();
			scroller.init();
		});
	};
})(jQuery);
