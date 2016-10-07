/**
 * Mobile Nav Plugin
 * Activates a mobile navigation menu which is hidden by default and can be toggled by the user.
 * @author: Luke Peavey
 * @updated: 9-5-16
 * @requires: matchMedia
 * @requires: GSAP (for animations)
 */
const MobileNavDrawer = (( $ ) => {
    "use strict";

    /**
     * the default settings used for MobileNavDrawer instances
     * @type {object}
     * @private
     */
    let _defaults = {
        breakpoint            : 768,
        openSpeed             : null,
        closeSpeed            : null,
        speed                 : 200,
        pageWrapperSelector   : '#page',
        onSwitchFromMobileNav : function () {
        },
        onSwitchToMobileNav   : function () {
        },
        mobileNavClass        : 'mobile-nav-drawer',
    };



    class _MobileNavDrawer {

        constructor( selector, options ) {
            this.options = $.extend( {}, _defaults, options );
            let nav      = this.nav = $( selector );
            if ( ! nav.length ) {
                return;
            }

            this.mediaQuery = matchMedia( '(max-width: ' + this.options.breakpoint + 'px)' );
            this.mediaQuery.addListener( this.mobileNavActivationHandler.bind( this ) );

            this.isOpen         = false;
            this.isMobile       = this.mediaQuery.matches;
            this.navParent      = nav.parent();
            this.navNextSibling = nav.next();
            this.pageWrapper    = $( this.options.pageWrapperSelector );
            this.toggleBtn      = $( '#nav-toggle' );

            this.mobileNavActivationHandler( this.mediaQuery );
        }

        /**
         * Activates/deactivates the mobileNav at the specified breakpoint
         * @param: mediaQuery {matchMedia Object}
         */
        mobileNavActivationHandler( mediaQuery ) {
            // switch to mobile nav
            if ( mediaQuery.matches ) {
                console.log('to mobile');
                // move the move the nav element outside the page wrapper
                this.nav.insertBefore( this.pageWrapper );
                this.nav.addClass( this.options.mobileNavClass );

                // switch back to desktop nav
            } else {
                console.log('to desktop');
               if (this.isOpen) {
                   this.close(0);
               }

                // move the nav back to original location in the DOM
                this.navNextSibling.length[ 0 ] ? this.navNextSibling.before( this.nav ) : this.navParent.append( this.nav );

                this.nav.removeClass( this.options.mobileNavClass );

            }
        }

        /**
         * Opens the mobile nav menu (animates it into view)
         */
        open(speed) {
            let self  = this;
            let elems = [ this.pageWrapper, this.nav ];
            let duration = ( typeof speed === 'number' ? parseInt(speed) : _defaults.speed ) / 1000;

            TweenMax.to( elems, duration, {
                x          : '-270px',
                ease       : Power1.easeOut,
                onComplete : function () {
                    $(document.body).addClass( 'mobile-nav-drawer--open' );
                    self.toggleBtn.addClass('is-active');
                    self.isOpen = true;
                }

            } )
        }

        /**
         * closes the mobile nav menu (animates it out of view)
         */
        close(speed) {
            console.log('close');
            let self  = this;
            let elems = [ this.pageWrapper, this.nav ];
            let duration = ( typeof speed === 'number' ? parseInt(speed) : _defaults.speed ) / 1000;
            TweenMax.to( elems, duration, {
                x      : 0,
                ease       : Power1.easeOut,
                onComplete : function () {
                    self.isOpen = false;
                    $(document.body ).removeClass( 'mobile-nav-drawer--open' );
                    self.toggleBtn.removeClass('is-active');
                    $( window ).trigger( 'resize' );
                }
            } )
        }

        toggle() {
            if ( this.isOpen ) {
                this.close();
            } else {
                this.open();
            }
        }

    }

    return _MobileNavDrawer;
})( jQuery );

$( function () {
    let mobileNavDrawer = new MobileNavDrawer( ".main-navigation" );
    $( '#nav-toggle' ).on( 'click', function () {
        mobileNavDrawer.toggle();
    } )

} );