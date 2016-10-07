<?php
/**
 * default search form
 */
?>
<form role="search" method="get" id="search-form" class="search-bar" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="s"><?php _e( 'Search For', 'lawpress' ); ?></label>
	<div class="input-group search-bar" >
		<input class="form-control" type="search" placeholder="<?php echo esc_attr( 'Search...',
			'lawpress' ); ?>" name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>"/>
        <span class="input-group-btn">
            <button type="submit" class="btn btn-secondary-outline" id="search-submit" value="Search">
	            <i class="fa fa-search"></i>
            </button>
        </span>
	</div>
</form>