<?php
/**
 * Elementor Blocks
 *
 * @package Copy the Code
 * @since 3.1.0
 */

namespace CopyTheCode\Elementor;

use CopyTheCode\Elementor\Block\Email\Sample as EmailSample;
use CopyTheCode\Elementor\Block\Email\Address as EmailAddress;
use CopyTheCode\Elementor\Block\PhoneNumber;
use CopyTheCode\Elementor\Block\CopyButton;
use CopyTheCode\Elementor\Block\CopyIcon;
use CopyTheCode\Elementor\Block\Blockquote;
use CopyTheCode\Elementor\Block\CodeSnippet;
use CopyTheCode\Elementor\Block\Message;
use CopyTheCode\Elementor\Block\Deal;
use CopyTheCode\Elementor\Block\Coupon;
use CopyTheCode\Elementor\Block\AI\Prompt\Generator as AIPromptGenerator;
use CopyTheCode\Elementor\Block\Wish;
use CopyTheCode\Elementor\Block\Shayari;
use CopyTheCode\Elementor\Block\SMS;
use CopyTheCode\Elementor\Block\Table;
use CopyTheCode\Elementor\Block\ContactInformation;

/**
 * Blocks
 *
 * @since 3.1.0
 */
class Blocks {

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
			return;
		}

		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
	}

	/**
	 * Register category
	 *
	 * @param object $elements_manager
	 *
	 * @since 3.1.0
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'copy-the-code',
			[
				'title' => esc_html__( 'Copy Anything to Clipboard', 'copy-the-code' ),
				'icon'  => 'fa fa-code',
			]
		);
	}

	/**
	 * Register widgets
	 *
	 * @param object $widgets_manager
	 *
	 * @since 3.1.0
	 */
	public function register_widgets( $widgets_manager ) {
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/email-sample/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/email-address/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/phone-number/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/copy-button/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/copy-icon/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/blockquote/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/code-snippet/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/message/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/wish/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/shayari/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/sms/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/deal/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/coupon/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/ai-prompt-generator/widget.php';
		require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/table/widget.php';
        require_once COPY_THE_CODE_DIR . 'classes/elementor/widgets/contact-information/widget.php';

		$widgets_manager->register( new EmailSample() );
		$widgets_manager->register( new EmailAddress() );
		$widgets_manager->register( new PhoneNumber() );
		$widgets_manager->register( new CopyButton() );
		$widgets_manager->register( new CopyIcon() );
		$widgets_manager->register( new Blockquote() );
		$widgets_manager->register( new CodeSnippet() );
		$widgets_manager->register( new Message() );
		$widgets_manager->register( new Wish() );
		$widgets_manager->register( new Shayari() );
		$widgets_manager->register( new SMS() );
		$widgets_manager->register( new Deal() );
		$widgets_manager->register( new Coupon() );
		$widgets_manager->register( new AIPromptGenerator() );
		$widgets_manager->register( new Table() );
        $widgets_manager->register( new ContactInformation() );
	}

	/**
	 * Get blocks
	 *
	 * @return array
	 *
	 * @since 3.1.0
	 */
	public static function get_blocks() {
		return [
			[
				'id'          => 'email-sample',
				'name'        => 'Email Sample',
				'description' => 'Create the email sample and allow users to copy it.',
			],
			[
				'id'          => 'email-address',
				'name'        => 'Email Address',
				'description' => 'Add the email address and allow users to copy it.',
			],
			[
				'id'          => 'phone-number',
				'name'        => 'Phone Number',
				'description' => 'Add the phone number and allow users to copy it.',
			],
			[
				'id'          => 'copy-button',
				'name'        => 'Copy Button',
				'description' => 'Add the copy button and add the hidden content which you want to copy.',
			],
			[
				'id'          => 'copy-icon',
				'name'        => 'Copy Icon',
				'description' => 'Add the copy icon and add the hidden content which you want to copy.',
			],
			[
				'id'          => 'blockquote',
				'name'        => 'Blockquote',
				'description' => 'Create the blockquote and allow users to copy the text.',
			],
			[
				'id'          => 'code-snippet',
				'name'        => 'Code Snippet',
				'description' => 'Create the code snippet and allow users to copy the text.',
			],
		];
	}

}
