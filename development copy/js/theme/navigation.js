// Navigation click to section.
$( function () {
  var header    = $( '#masthead' );
  var topbar    = $( '.top-bar' );
  var mainMenu  = $( '#site-navigation' );
  var navToggle = $( '#nav-toggle' );
  var isMobile  = false;

  mainMenu.find( '.nav-link' ).on( 'click', function ( evt ) {
    $( this ).blur();

  } );
  mainMenu.find( '.dropdown-toggle' ).each( function ( i, node ) {
    var elem = $( node ), dropdownMenuId, dropdownToggleId;
    // add panel class to the li element that contains the dropdown menu
    elem.parent().addClass( 'panel' );
    // Add some markup to dropdown links in the main menu
    elem.append( '<div class="nav-toggle-subarrow hidden-md-up"><i class="fa fa-plus hidden-md-up"></i></div>' )
    .attr( 'data-target', '#' + ( dropdownMenuId = 'sub-menu-' + i ) )

    // Add markup the sub-menus (the ul tag containing sub-menu items)
    .siblings( 'ul' ).addClass( 'dropdown-menu' )
    .attr( 'id', dropdownMenuId )
    .attr( 'data-parent', '#site-navigation' )

    .attr( 'aria-labelledby', dropdownToggleId )
  } );

  //@TODO (LP) - Remove debug console.log statements
  function submenusToDropdowns() {
    console.log( 'converting mobile submenus to desktop dropdowns' );
    mainMenu
    .find( '.dropdown-toggle' )
    .attr( 'data-toggle', 'dropdown' )
    .attr( 'area-expanded', 'false' )
    .siblings( 'ul' )
    .addClass( 'dropdown-menu' )
    .removeClass( 'collapse collapse-panel in sub-menu' )
    // Remove event handlers for mobile nav sub menus
  }

  function dropDownsToSubmenus() {
    console.log( 'converting desktop dropdowns to mobile submenus' );
    mainMenu
    .find( '.dropdown-toggle' )
    .attr( 'data-toggle', 'collapse' )
    .attr( 'area-expanded', 'false' )
    .siblings( 'ul' )
    .addClass( 'collapse collapse-panel sub-menu' ).removeClass( 'dropdown-menu open' )
  }

  var mediaQuery = window.matchMedia( '(min-width:768px)' );

  function prepareNavMenu() {
    $( 'body' ).toggleClass( 'is-mobile', ( isMobile = ! mediaQuery.matches) );
    if ( isMobile ) {
      dropDownsToSubmenus();
    } else {
      // class the mobile nav menu and any sub menus when switching to large screen layout.

      $( '#site-navigation' ).find( '.collapse' ).collapse( 'hide' );
      submenusToDropdowns();
    }
  }

  prepareNavMenu();
  mediaQuery.addListener( prepareNavMenu );

  // Toggle is-active class on dropdown links when the accociated submenu
  // is expanded/collapsed.
  mainMenu.on( 'hide.bs.collapse show.bs.collapse hidden.bs.collapse', function ( evt ) {
    if ( evt.type === 'hidden' ) {
      // Remove inline height = 0px from collapse elements after they have been closed.
      // bootstrap sets height to 0 when collapsible elements are closed. This inline
      // style is not needed once the tranistion is complete. I removed it because here
      // the collapse elements are only collapsible on small screen, so the 0 height was
      // causing problems when screen size changed.
      evt.target.style.height = '';
      // When the mainMenu is closed, collapse any open submenus
      if ( mainMenu.is( evt.target ) ) {
        mainMenu.find( '.collapse' ).collapse( 'hide' );
      }
    } else {
      $( evt.target ).siblings( '.dropdown-toggle, .nav-toggle' )
      .toggleClass( 'is-active', evt.type === 'show' );
    }
  } );

  /*******************************************************
   Onepage Navigation
   *******************************************************/

  $( '#main-menu a, .scroll-to-top ' ).on( 'click', function ( event ) {
    if ( this.nodeName.toLowerCase() === 'a' ) {
      var currentUrl = trailingslashit( window.location.href.replace( window.location.hash, '' ) );
      var linkUrl    = trailingslashit( this.href.replace( this.hash, '' ) );
      var offset;
      var scrollToElement;

      if ( offset = this.getAttribute( 'data-scroll-to' ) ) {
        event.preventDefault();
        smoothScroll( $('html'), { offset : offset } );

      } else if ( currentUrl === linkUrl && this.hash !== '' ) {
        event.preventDefault();
        scrollToElement = ( this.hash === '#home' ) ? $('html') : this.hash;
        offset          = ( this.hash === '#home' ) ? 0 : 64;

        smoothScroll( scrollToElement, { offset : offset } )
      }
    }
  } );


  /*******************************************************
   Sticky Header
   *******************************************************/

  var StickyHeader = {
    elem                : $( '#masthead' ),
    isActive            : false,
    isAnimating         : false,
    retry               : 0,
    activationThreshold : 450
  };

  function stickyHeaderActivationHandler( evt ) {
    if ( !$('body' ).hasClass('transparent-header') ) return;
    if ( StickyHeader.isAnimating ) {
      StickyHeader.retry = 1
      return;
    }

    if ( window.scrollY > StickyHeader.activationThreshold && ! StickyHeader.isActive ) {


      StickyHeader.elem.addClass( 'sticky' );
      TweenMax.killChildTweensOf(StickyHeader.elem);
      TweenMax.set(StickyHeader.elem, {opacity: 0.3, y: '-100%'});
      TweenMax.to(StickyHeader.elem, 0.3, {
        opacity: 1.0,
        y: '0%',
        visibility: 'visible',
        onStart:  function () {
          StickyHeader.isAnimating = true;
        },
        onComplete: function () {
          StickyHeader.elem.addClass( 'in' );
          StickyHeader.isActive    = true;
          StickyHeader.isAnimating = false;
          if ( StickyHeader.retry ) {
            stickyHeaderActivationHandler();
          }
        }
      });




    } else if ( ( window.scrollY < StickyHeader.activationThreshold ) && StickyHeader.isActive ) {
      TweenMax.killChildTweensOf(StickyHeader.elem);
      TweenMax.to(StickyHeader.elem, 0.5, {
        opacity: 0.75,
        y: '-100%',
        ease: Power3.easeInOut,
        onStart:  function () {
          StickyHeader.isAnimating = true;
        },
        onComplete: function () {
          StickyHeader.elem.removeClass( 'in sticky' ).get(0).style.cssText = '';
          StickyHeader.isActive = StickyHeader.isAnimating = false;
        }
      });
    }

    StickyHeader.retry = 0;
  }

   $(window).on('scroll', stickyHeaderActivationHandler);
   stickyHeaderActivationHandler();
} );