<?php
/**
 * martialwc Admin Class.
 *
 * @author  WebAtome
 * @package martialwc
 * @since   1.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'martialwc_Admin' ) ) :

/**
 * martialwc_Admin Class.
 */
class martialwc_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
		add_action( 'load-themes.php', array( $this, 'admin_notice' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function admin_menu() {
		$theme = wp_get_theme( get_template() );

		$page = add_theme_page( esc_html__( 'About', 'martialwc' ) . ' ' . $theme->display( 'Name' ), esc_html__( 'About', 'martialwc' ) . ' ' . $theme->display( 'Name' ), 'activate_plugins', 'martialwc-welcome', array( $this, 'welcome_screen' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function enqueue_styles() {
		global $martialwc_version;

		wp_enqueue_style( 'martialwc-welcome', get_template_directory_uri() . '/css/admin/welcome.css', array(), $martialwc_version );
	}

	/**
	 * Add admin notice.
	 */
	public function admin_notice() {
		global $martialwc_version, $pagenow;

		wp_enqueue_style( 'martialwc-message', get_template_directory_uri() . '/css/admin/message.css', array(), $martialwc_version );


		// Let's bail on theme activation.
		if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
			update_option( 'martialwc_admin_notice_welcome', 1 );
		// No option? Let run the notice wizard again..
		} elseif( ! get_option( 'martialwc_admin_notice_welcome' ) ) {
			add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
		}
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		if ( isset( $_GET['martialwc-hide-notice'] ) && isset( $_GET['_martialwc_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_martialwc_notice_nonce'], 'martialwc_hide_notices_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'martialwc' ) );
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'martialwc' ) );
			}
			$hide_notice = sanitize_text_field( $_GET['martialwc-hide-notice'] );
			update_option( 'martialwc_admin_notice_' . $hide_notice, 1 );
		}
	}

	/**
	 * Show welcome notice.
	 */
	public function welcome_notice() {
		?>
		<div id="message" class="updated martialwc-message">
			<a class="martialwc-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'martialwc-hide-notice', 'welcome' ) ), 'martialwc_hide_notices_nonce', '_martialwc_notice_nonce' ) ); ?>"><?php _e( 'Dismiss', 'martialwc' ); ?></a>
			<p><?php printf( esc_html__( 'Welcome! Thank you for choosing martialwc! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page%s.', 'martialwc' ), '<a href="' . esc_url( admin_url( 'themes.php?page=martialwc-welcome' ) ) . '">', '</a>' ); ?></p>
			<p class="submit">
				<a class="button-secondary" href="<?php echo esc_url( admin_url( 'themes.php?page=martialwc-welcome' ) ); ?>"><?php esc_html_e( 'Get started with martialwc', 'martialwc' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Intro text/links shown to all about pages.
	 *
	 * @access private
	 */
	private function intro() {
		global $martialwc_version;
		$theme = wp_get_theme( get_template() );

		// Drop minor version if 0
		$major_version = substr( $martialwc_version, 0, 3 );
		?>
		<div class="martialwc-theme-info">
				<h1>
					<?php esc_html_e('About', 'martialwc'); ?>
					<?php echo $theme->display( 'Name' ); ?>
					<?php printf( esc_html__( '%s', 'martialwc' ), $major_version ); ?>
				</h1>

			<div class="welcome-description-wrap">
				<div class="about-text"><?php echo $theme->display( 'Description' ); ?></div>

				<div class="martialwc-screenshot">
					<img src="<?php echo esc_url( get_template_directory_uri() ) . '/screenshot.jpg'; ?>" />
				</div>
			</div>
		</div>

		<p class="martialwc-actions">
			<a href="<?php echo esc_url( 'https://WebAtome.com/themes/martialwc/' ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Theme Info', 'martialwc' ); ?></a>

			<a href="<?php echo esc_url( apply_filters( 'martialwc_pro_theme_url', 'https://demo.WebAtome.com/martialwc/' ) ); ?>" class="button button-secondary docs" target="_blank"><?php esc_html_e( 'View Demo', 'martialwc' ); ?></a>

			<a href="<?php echo esc_url( apply_filters( 'martialwc_pro_theme_url', 'https://WebAtome.com/themes/martialwc-pro/' ) ); ?>" class="button button-primary docs" target="_blank"><?php esc_html_e( 'View Pro', 'martialwc' ); ?></a>

			<a href="<?php echo esc_url( apply_filters( 'martialwc_pro_theme_url', 'https://wordpress.org/support/theme/martialwc/reviews/?filter=5' ) ); ?>" class="button button-secondary docs" target="_blank"><?php esc_html_e( 'Rate this theme', 'martialwc' ); ?></a>
		</p>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( empty( $_GET['tab'] ) && $_GET['page'] == 'martialwc-welcome' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'martialwc-welcome' ), 'themes.php' ) ) ); ?>">
				<?php echo $theme->display( 'Name' ); ?>
			</a>
			<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'supported_plugins' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'martialwc-welcome', 'tab' => 'supported_plugins' ), 'themes.php' ) ) ); ?>">
				<?php esc_html_e( 'Supported Plugins', 'martialwc' ); ?>
			</a>
			<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'free_vs_pro' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'martialwc-welcome', 'tab' => 'free_vs_pro' ), 'themes.php' ) ) ); ?>">
				<?php esc_html_e( 'Free Vs Pro', 'martialwc' ); ?>
			</a>
			<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'changelog' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'martialwc-welcome', 'tab' => 'changelog' ), 'themes.php' ) ) ); ?>">
				<?php esc_html_e( 'Changelog', 'martialwc' ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Welcome screen page.
	 */
	public function welcome_screen() {
		$current_tab = empty( $_GET['tab'] ) ? 'about' : sanitize_title( $_GET['tab'] );

		// Look for a {$current_tab}_screen method.
		if ( is_callable( array( $this, $current_tab . '_screen' ) ) ) {
			return $this->{ $current_tab . '_screen' }();
		}

		// Fallback to about screen.
		return $this->about_screen();
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		$theme = wp_get_theme( get_template() );
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<div class="changelog point-releases">
				<div class="under-the-hood two-col">
					<div class="col">
						<h3><?php esc_html_e( 'Theme Customizer', 'martialwc' ); ?></h3>
						<p><?php esc_html_e( 'All Theme Options are available via Customize screen.', 'martialwc' ) ?></p>
						<p><a href="<?php echo admin_url( 'customize.php' ); ?>" class="button button-secondary"><?php esc_html_e( 'Customize', 'martialwc' ); ?></a></p>
					</div>

					<div class="col">
						<h3><?php esc_html_e( 'Documentation', 'martialwc' ); ?></h3>
						<p><?php esc_html_e( 'Please view our documentation page to setup the theme.', 'martialwc' ) ?></p>
						<p><a href="<?php echo esc_url( 'https://docs.WebAtome.com/martialwc/' ); ?>" class="button button-secondary"><?php esc_html_e( 'Documentation', 'martialwc' ); ?></a></p>
					</div>

					<div class="col">
						<h3><?php esc_html_e( 'Got theme support question?', 'martialwc' ); ?></h3>
						<p><?php esc_html_e( 'Please put it in our dedicated support forum.', 'martialwc' ) ?></p>
						<p><a href="<?php echo esc_url( 'https://WebAtome.com/support-forum/' ); ?>" class="button button-secondary"><?php esc_html_e( 'Support Forum', 'martialwc' ); ?></a></p>
					</div>

					<div class="col">
						<h3><?php esc_html_e( 'Need more features?', 'martialwc' ); ?></h3>
						<p><?php esc_html_e( 'Upgrade to PRO version for more exciting features.', 'martialwc' ) ?></p>
						<p><a href="<?php echo esc_url( 'https://WebAtome.com/themes/martialwc-pro/' ); ?>" class="button button-secondary"><?php esc_html_e( 'View Pro', 'martialwc' ); ?></a></p>
					</div>

					<div class="col">
						<h3><?php esc_html_e( 'Got sales related question?', 'martialwc' ); ?></h3>
						<p><?php esc_html_e( 'Please send it via our sales contact page.', 'martialwc' ) ?></p>
						<p><a href="<?php echo esc_url( 'https://WebAtome.com/contact/' ); ?>" class="button button-secondary"><?php esc_html_e( 'Contact Page', 'martialwc' ); ?></a></p>
					</div>

					<div class="col">
						<h3>
							<?php
							esc_html_e( 'Translate', 'martialwc' );
							echo ' ' . $theme->display( 'Name' );
							?>
						</h3>
						<p><?php esc_html_e( 'Click below to translate this theme into your own language.', 'martialwc' ) ?></p>
						<p>
							<a href="<?php echo esc_url( 'http://translate.wordpress.org/projects/wp-themes/martialwc' ); ?>" class="button button-secondary">
								<?php
								esc_html_e( 'Translate', 'martialwc' );
								echo ' ' . $theme->display( 'Name' );
								?>
							</a>
						</p>
					</div>
				</div>
			</div>

			<div class="return-to-dashboard martialwc">
				<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
					<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
						<?php is_multisite() ? esc_html_e( 'Return to Updates', 'martialwc' ) : esc_html_e( 'Return to Dashboard &rarr; Updates', 'martialwc' ); ?>
					</a> |
				<?php endif; ?>
				<a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? esc_html_e( 'Go to Dashboard &rarr; Home', 'martialwc' ) : esc_html_e( 'Go to Dashboard', 'martialwc' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the changelog screen.
	 */
	public function changelog_screen() {
		global $wp_filesystem;

		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php esc_html_e( 'View changelog below.', 'martialwc' ); ?></p>

			<?php
				$changelog_file = apply_filters( 'martialwc_changelog_file', get_template_directory() . '/readme.txt' );

				// Check if the changelog file exists and is readable.
				if ( $changelog_file && is_readable( $changelog_file ) ) {
					WP_Filesystem();
					$changelog = $wp_filesystem->get_contents( $changelog_file );
					$changelog_list = $this->parse_changelog( $changelog );

					echo wp_kses_post( $changelog_list );
				}
			?>
		</div>
		<?php
	}

	/**
	 * Parse changelog from readme file.
	 * @param  string $content
	 * @return string
	 */
	private function parse_changelog( $content ) {
		$matches   = null;
		$regexp    = '~==\s*Changelog\s*==(.*)($)~Uis';
		$changelog = '';

		if ( preg_match( $regexp, $content, $matches ) ) {
			$changes = explode( '\r\n', trim( $matches[1] ) );

			$changelog .= '<pre class="changelog">';

			foreach ( $changes as $index => $line ) {
				$changelog .= wp_kses_post( preg_replace( '~(=\s*Version\s*(\d+(?:\.\d+)+)\s*=|$)~Uis', '<span class="title">${1}</span>', $line ) );
			}

			$changelog .= '</pre>';
		}

		return wp_kses_post( $changelog );
	}

	/**
	 * Output the supported plugins screen.
	 */
	public function supported_plugins_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php esc_html_e( 'This theme recommends following plugins.', 'martialwc' ); ?></p>
			<ol>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/social-icons/' ); ?>" target="_blank"><?php esc_html_e( 'Social Icons', 'martialwc' ); ?></a>
					<?php esc_html_e(' by WebAtome', 'martialwc'); ?>
				</li>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/easy-social-sharing/' ); ?>" target="_blank"><?php esc_html_e( 'Easy Social Sharing', 'martialwc' ); ?></a>
					<?php esc_html_e(' by WebAtome', 'martialwc'); ?>
				</li>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/contact-form-7/' ); ?>" target="_blank"><?php esc_html_e( 'Contact Form 7', 'martialwc' ); ?></a></li>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/wp-pagenavi/' ); ?>" target="_blank"><?php esc_html_e( 'WP-PageNavi', 'martialwc' ); ?></a></li>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/woocommerce/' ); ?>" target="_blank"><?php esc_html_e( 'WooCommerce', 'martialwc' ); ?></a></li>
				<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/yith-woocommerce-wishlist/' ); ?>" target="_blank"><?php esc_html_e( 'YITH WooCommerce Wishlist', 'martialwc' ); ?></a>
				</li>
				<li><a href="<?php echo esc_url( 'https://wpml.org/' ); ?>" target="_blank"><?php esc_html_e( 'WPML', 'martialwc' ); ?></a>
				</li>
			</ol>

		</div>
		<?php
	}

	/**
	 * Output the free vs pro screen.
	 */
	public function free_vs_pro_screen() {
		?>
		<div class="wrap about-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php esc_html_e( 'Upgrade to PRO version for more exciting features.', 'martialwc' ); ?></p>

			<table>
				<thead>
					<tr>
						<th class="table-feature-title"><h3><?php esc_html_e('Features', 'martialwc'); ?></h3></th>
						<th><h3><?php esc_html_e('martialwc', 'martialwc'); ?></h3></th>
						<th><h3><?php esc_html_e('martialwc Pro', 'martialwc'); ?></h3></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><h3><?php esc_html_e('Different Header Layouts', 'martialwc'); ?></h3></td>
						<td><?php esc_html_e('Default header layout (free)', 'martialwc'); ?></td>
						<td><?php esc_html_e('3 different header layouts', 'martialwc'); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Google Fonts Option', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><?php esc_html_e('600+', 'martialwc'); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Font Size options', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Color Palette', 'martialwc'); ?></h3></td>
						<td><?php esc_html_e('Primary Color Option', 'martialwc'); ?></td>
						<td><?php esc_html_e('Multiple Color Options', 'martialwc'); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Translation Ready', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Woocommerce Compatible', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('YITH Wishlist Compatible', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('WPML Compatible', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('RTL Support', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Footer Copyright Editor', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Footer Widgets Column', 'martialwc'); ?></h3></td>
						<td><?php esc_html_e('1,2,3,4 Columns', 'martialwc'); ?></td>
						<td><?php esc_html_e('1,2,3,4 Columns', 'martialwc'); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Demo Content', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Support', 'martialwc'); ?></h3></td>
						<td><?php esc_html_e('Forum', 'martialwc'); ?></td>
						<td><?php esc_html_e('Forum + Emails/Support Ticket', 'martialwc'); ?></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('Extra Options on Widgets', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: About Widget', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Advertisement', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Category Carousel', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Category Grid', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Category Slider', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Featured Posts', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Horizontal Promo', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Horizontal Promo WC Category', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Logo', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Product grid', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Logos', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Products Carousel', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Product slider', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Vertical Promo', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Vertical Promo WC Category', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-yes"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Testimonials', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Icon Text', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td><h3><?php esc_html_e('TG: Products Tab', 'martialwc'); ?></h3></td>
						<td><span class="dashicons dashicons-no"></span></td>
						<td><span class="dashicons dashicons-yes"></span></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="btn-wrapper">
							<a href="<?php echo esc_url( apply_filters( 'martialwc_pro_theme_url', 'https://WebAtome.com/themes/martialwc-pro/' ) ); ?>" class="button button-secondary docs" target="_blank"><?php _e( 'View Pro', 'martialwc' ); ?></a>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
		<?php
	}
}

endif;

return new martialwc_Admin();
