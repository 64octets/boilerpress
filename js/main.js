'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol ? "symbol" : typeof obj; };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function ($, window, document) {
  var $body = $('body');
  var breakpoints = {
    xs: 0,
    sm: 540,
    md: 768,
    lg: 992,
    xl: 1200
  };

  var mediaQueries = {};

  Object.keys(breakpoints).forEach(function (key) {
    mediaQueries[key] = window.matchMedia('(min-width:' + breakpoints[key] + 'px)');
  });

  function mobileHeroContent(mq) {
    var elem = $('#mobile-hero-content');
    if (mq.matches) {
      TweenMax.to(elem, 0.4, {
        height: 0,
        delay: 0.2,
        ease: Power2.easeOut,
        display: 'none'
      });
    } else {
      TweenMax.to(elem, 0.4, {
        height: '275px',
        delay: 0.2,
        ease: Power2.easeOut,
        display: 'block'
      });
    }
  }
  mediaQueries['md'].addListener(mobileHeroContent);
  mobileHeroContent(mediaQueries['md']);

  /**
   * Smoothly scrolls the page to a specific location, either a target element
   * or an arbitrary offset value (in pixels). By default, the duration of the
   * scroll animation is set relative to the distance being scrolled.
   *
   * @param  target: the target element to scroll to
   * @param  params: an object with custom options for scroll the animation. Takes the
   *         same options as a velocity call (see velocity.js)
   */

  function isObject(obj) {
    return (typeof obj === 'undefined' ? 'undefined' : _typeof(obj)) === 'object' && obj !== null;
  }

  var SmoothScroll = {};
  function smoothScroll(target, params) {
    target = $(target);
    var targetNode = target.get(0),
        scrollDistance,
        options;

    if (!targetNode) {
      return;
    }

    if (targetNode === document.body || targetNode === window.window) {
      scrollDistance = 0;
    } else {
      scrollDistance = Math.abs(target.offset().top - window.pageYOffset) * 0.25;
    }

    options = $.extend({}, {
      duration: Math.max(750, Math.min(scrollDistance, 2000)),
      ease: Power2.easeOut,
      offset: 0
    }, isObject(params) ? params : {});

    if (window.Velocity) {
      // stop all active animations on the target and body element before starting
      // In case a scroll animation is currently in progress.
      $('html').velocity('stop');
      return target.velocity('stop').velocity('scroll', options);
    } else {
      var duration = options.duration * 0.001;
      TweenLite.to(window, duration, {
        scrollTo: targetNode.id ? {
          y: '#' + targetNode.id,
          offsetY: options.offset || 0
        } : 0
      });
    }
  }

  function killSmoothScroll() {
    if (State.smoothScrolling && isObject(State.smoothScrolling)) {
      $.Velocity(State.smoothScrolling(), 'stop');
    }
  }

  function trailingslashit(str) {
    if (typeof str !== 'string' || !str.length) return;
    return str[str.length - 1] === '/' ? str : str + '/';
  }
  function untrailinglashit(str) {
    if (typeof str !== 'string' || str.length === 0) return;
    return str[str.length - 1] === '/' ? str.substr(0, str.length - 1) : str;
  }

  // Navigation click to section.
  $(function () {
    var header = $('#masthead');
    var topbar = $('.top-bar');
    var mainMenu = $('#main-navigation');
    var navToggle = $('#nav-toggle');
    var isMobile = false;

    mainMenu.find('.nav-link').on('click', function (evt) {
      $(this).blur();
    });
    mainMenu.find('.dropdown-toggle').each(function (i, node) {
      var elem = $(node),
          dropdownMenuId,
          dropdownToggleId;
      // add panel class to the li element that contains the dropdown menu
      elem.parent().addClass('panel');
      // Add some markup to dropdown links in the main menu
      elem.append('<div class="nav-toggle-subarrow hidden-md-up"><i class="fa fa-plus hidden-md-up"></i></div>').attr('data-target', '#' + (dropdownMenuId = 'sub-menu-' + i))

      // Add markup the sub-menus (the ul tag containing sub-menu items)
      .siblings('ul').addClass('dropdown-menu').attr('id', dropdownMenuId).attr('data-parent', '#main-navigation').attr('aria-labelledby', dropdownToggleId);
    });

    //@TODO (LP) - Remove debug console.log statements
    function submenusToDropdowns() {
      console.log('converting mobile submenus to desktop dropdowns');
      mainMenu.find('.dropdown-toggle').attr('data-toggle', 'dropdown').attr('area-expanded', 'false').siblings('ul').addClass('dropdown-menu').removeClass('collapse collapse-panel in sub-menu');
      // Remove event handlers for mobile nav sub menus
    }

    function dropDownsToSubmenus() {
      console.log('converting desktop dropdowns to mobile submenus');
      mainMenu.find('.dropdown-toggle').attr('data-toggle', 'collapse').attr('area-expanded', 'false').siblings('ul').addClass('collapse collapse-panel sub-menu').removeClass('dropdown-menu open');
    }

    var mediaQuery = window.matchMedia('(min-width:768px)');

    function prepareNavMenu() {
      $('body').toggleClass('is-mobile', isMobile = !mediaQuery.matches);
      if (isMobile) {
        dropDownsToSubmenus();
      } else {
        // class the mobile nav menu and any sub menus when switching to large screen layout.

        $('#site-navigation').find('.collapse').collapse('hide');
        submenusToDropdowns();
      }
    }

    prepareNavMenu();
    mediaQuery.addListener(prepareNavMenu);

    // Toggle is-active class on dropdown links when the accociated submenu
    // is expanded/collapsed.
    mainMenu.on('hide.bs.collapse show.bs.collapse hidden.bs.collapse', function (evt) {
      if (evt.type === 'hidden') {
        // Remove inline height = 0px from collapse elements after they have been closed.
        // bootstrap sets height to 0 when collapsible elements are closed. This inline
        // style is not needed once the tranistion is complete. I removed it because here
        // the collapse elements are only collapsible on small screen, so the 0 height was
        // causing problems when screen size changed.
        evt.target.style.height = '';
        // When the mainMenu is closed, collapse any open submenus
        if (mainMenu.is(evt.target)) {
          mainMenu.find('.collapse').collapse('hide');
        }
      } else {
        $(evt.target).siblings('.dropdown-toggle, .nav-toggle').toggleClass('is-active', evt.type === 'show');
      }
    });

    /*******************************************************
     Onepage Navigation
     *******************************************************/

    $('#main-menu a, .scroll-to-top ').on('click', function (event) {
      if (this.nodeName.toLowerCase() === 'a') {
        var currentUrl = trailingslashit(window.location.href.replace(window.location.hash, ''));
        var linkUrl = trailingslashit(this.href.replace(this.hash, ''));
        var offset;
        var scrollToElement;

        if (offset = this.getAttribute('data-scroll-to')) {
          event.preventDefault();
          smoothScroll($('html'), { offset: offset });
        } else if (currentUrl === linkUrl && this.hash !== '') {
          event.preventDefault();
          scrollToElement = this.hash === '#home' ? $('html') : this.hash;
          offset = this.hash === '#home' ? 0 : 64;

          smoothScroll(scrollToElement, { offset: offset });
        }
      }
    });

    /*******************************************************
     Sticky Header
     *******************************************************/

    var StickyHeader = {
      elem: $('#masthead'),
      isActive: false,
      isAnimating: false,
      retry: 0,
      activationThreshold: 450
    };

    function stickyHeaderActivationHandler(evt) {
      if (!$('body').hasClass('transparent-header')) return;
      if (StickyHeader.isAnimating) {
        StickyHeader.retry = 1;
        return;
      }

      if (window.scrollY > StickyHeader.activationThreshold && !StickyHeader.isActive) {

        StickyHeader.elem.addClass('sticky');
        TweenMax.killChildTweensOf(StickyHeader.elem);
        TweenMax.set(StickyHeader.elem, { opacity: 0.3, y: '-100%' });
        TweenMax.to(StickyHeader.elem, 0.3, {
          opacity: 1.0,
          y: '0%',
          visibility: 'visible',
          onStart: function onStart() {
            StickyHeader.isAnimating = true;
          },
          onComplete: function onComplete() {
            StickyHeader.elem.addClass('in');
            StickyHeader.isActive = true;
            StickyHeader.isAnimating = false;
            if (StickyHeader.retry) {
              stickyHeaderActivationHandler();
            }
          }
        });
      } else if (window.scrollY < StickyHeader.activationThreshold && StickyHeader.isActive) {
        TweenMax.killChildTweensOf(StickyHeader.elem);
        TweenMax.to(StickyHeader.elem, 0.5, {
          opacity: 0.75,
          y: '-100%',
          ease: Power3.easeInOut,
          onStart: function onStart() {
            StickyHeader.isAnimating = true;
          },
          onComplete: function onComplete() {
            StickyHeader.elem.removeClass('in sticky').get(0).style.cssText = '';
            StickyHeader.isActive = StickyHeader.isAnimating = false;
          }
        });
      }

      StickyHeader.retry = 0;
    }

    $(window).on('scroll', stickyHeaderActivationHandler);
    stickyHeaderActivationHandler();
  });
  /**
   * Mobile Nav Plugin
   * Activates a mobile navigation menu which is hidden by default and can be toggled by the user.
   * @author: Luke Peavey
   * @updated: 9-5-16
   * @requires: matchMedia
   * @requires: GSAP (for animations)
   */
  var MobileNavDrawer = function ($) {
    "use strict";

    /**
     * the default settings used for MobileNavDrawer instances
     * @type {object}
     * @private
     */

    var _defaults = {
      breakpoint: 768,
      openSpeed: null,
      closeSpeed: null,
      speed: 200,
      pageWrapperSelector: '#page',
      onSwitchFromMobileNav: function onSwitchFromMobileNav() {},
      onSwitchToMobileNav: function onSwitchToMobileNav() {},
      mobileNavClass: 'mobile-nav-drawer'
    };

    var _MobileNavDrawer = function () {
      function _MobileNavDrawer(selector, options) {
        _classCallCheck(this, _MobileNavDrawer);

        this.options = $.extend({}, _defaults, options);
        var nav = this.nav = $(selector);
        if (!nav.length) {
          return;
        }

        this.mediaQuery = matchMedia('(max-width: ' + this.options.breakpoint + 'px)');
        this.mediaQuery.addListener(this.mobileNavActivationHandler.bind(this));

        this.isOpen = false;
        this.isMobile = this.mediaQuery.matches;
        this.navParent = nav.parent();
        this.navNextSibling = nav.next();
        this.pageWrapper = $(this.options.pageWrapperSelector);
        this.toggleBtn = $('#nav-toggle');

        this.mobileNavActivationHandler(this.mediaQuery);
      }

      /**
       * Activates/deactivates the mobileNav at the specified breakpoint
       * @param: mediaQuery {matchMedia Object}
       */


      _createClass(_MobileNavDrawer, [{
        key: 'mobileNavActivationHandler',
        value: function mobileNavActivationHandler(mediaQuery) {
          // switch to mobile nav
          if (mediaQuery.matches) {
            console.log('to mobile');
            // move the move the nav element outside the page wrapper
            this.nav.insertBefore(this.pageWrapper);
            this.nav.addClass(this.options.mobileNavClass);

            // switch back to desktop nav
          } else {
            console.log('to desktop');
            if (this.isOpen) {
              this.close(0);
            }

            // move the nav back to original location in the DOM
            this.navNextSibling.length[0] ? this.navNextSibling.before(this.nav) : this.navParent.append(this.nav);

            this.nav.removeClass(this.options.mobileNavClass);
          }
        }

        /**
         * Opens the mobile nav menu (animates it into view)
         */

      }, {
        key: 'open',
        value: function open(speed) {
          var self = this;
          var elems = [this.pageWrapper, this.nav];
          var duration = (typeof speed === 'number' ? parseInt(speed) : _defaults.speed) / 1000;

          TweenMax.to(elems, duration, {
            x: '-270px',
            ease: Power1.easeOut,
            onComplete: function onComplete() {
              $(document.body).addClass('mobile-nav-drawer--open');
              self.toggleBtn.addClass('is-active');
              self.isOpen = true;
            }

          });
        }

        /**
         * closes the mobile nav menu (animates it out of view)
         */

      }, {
        key: 'close',
        value: function close(speed) {
          console.log('close');
          var self = this;
          var elems = [this.pageWrapper, this.nav];
          var duration = (typeof speed === 'number' ? parseInt(speed) : _defaults.speed) / 1000;
          TweenMax.to(elems, duration, {
            x: 0,
            ease: Power1.easeOut,
            onComplete: function onComplete() {
              self.isOpen = false;
              $(document.body).removeClass('mobile-nav-drawer--open');
              self.toggleBtn.removeClass('is-active');
              $(window).trigger('resize');
            }
          });
        }
      }, {
        key: 'toggle',
        value: function toggle() {
          if (this.isOpen) {
            this.close();
          } else {
            this.open();
          }
        }
      }]);

      return _MobileNavDrawer;
    }();

    return _MobileNavDrawer;
  }(jQuery);

  $(function () {
    var mobileNavDrawer = new MobileNavDrawer(".main-navigation");
    $('#nav-toggle').on('click', function () {
      mobileNavDrawer.toggle();
    });
  });

  $(window).on('load', function () {
    var parallaxSectionOne = $("#parallax-section-one");
    var parallaxSectionTwo = $("#parallax-section-two");
    if (parallaxSectionOne.length && parallaxSectionTwo.length && 0) {
      TweenMax.set(".headline.display-1", { y: '-200px', scale: 0.2, opacity: 0.0 });
      // init controller
      var controller = new ScrollMagic.Controller({
        globalSceneOptions: {
          triggerHook: "onEnter",
          offset: 0,
          duration: "200%"
        }
      });

      // build scenes
      new ScrollMagic.Scene({ triggerElement: "#parallax-section-one" }).setTween("#parallax-section-one > .parallax-background", { y: "30%", ease: Linear.easeNone }).addTo(controller);

      // build scenes
      new ScrollMagic.Scene({ triggerElement: "#parallax-section-one" }).setTween(".headline.display-1", { y: '0px', scale: 1.0, opacity: 1.0, ease: Linear.easeNone }).addTo(controller);

      new ScrollMagic.Scene({ triggerElement: "#parallax-section-two" }).setTween("#parallax-section-two > .parallax-background", { y: "30%", ease: Linear.easeNone }).addTo(controller);
    }
  });
  $(function () {
    // Parallax sections are handled with GSAP and ScrollMagic
    var parallaxSections = $('.parallax-section');
    var controller = new ScrollMagic.Controller({
      globalSceneOptions: {
        triggerHook: "onEnter",
        offset: 0,
        duration: "150%"
      }
    });

    if (parallaxSections.length) {

      // Setup each parallax section on the page.
      parallaxSections.each(function (_, elem) {
        var SECTION_ID = elem && '#' + elem.id;

        // The section needs to have an ID
        if (SECTION_ID) {
          var scene = new ScrollMagic.Scene({ triggerElement: SECTION_ID });
          scene.setTween(SECTION_ID + ' .parallax-background', { y: '30%', ease: Linear.easeNone });
          scene.addTo(controller);
        }
      }); // END EACH
    } // END IF;
  });
})(jQuery, window, document);