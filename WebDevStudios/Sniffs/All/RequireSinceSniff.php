<?php // @codingStandardsIgnoreLine: Filename is ok.
/**
 * Require the since tag.
 *
 * The since tag is required on all docblock elements.
 *
 * @since   1.1.0
 * @package WebDevStudios\Sniffs
 */

namespace WebDevStudios\Sniffs\All;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Require the return tag.
 *
 * @author Aubrey Portwood
 * @since  1.1.0
 */
class RequireSinceSniff extends BaseSniff {

	/**
	 * What are we parsing?
	 *
	 * @author Aubrey Portwood
	 * @since  1.1.0
	 *
	 * @var array
	 */
	public $supportedTokenizers = [ // @codingStandardsIgnoreLine: camelCase required here.
		'PHP',
		'JS',
	];

	/**
	 * Register on all docblock comments.
	 *
	 * @author Aubrey Portwood
	 * @since  1.1.0
	 *
	 * @return array List of tokens.
	 */
	public function register() {
		return [

			/**
			 * PHP/JS Docblock.
			 *
			 * @link http://php.net/manual/en/language.basic-syntax.comments.php
			 *
			 * @since 1.1.0
			 */
			T_DOC_COMMENT_OPEN_TAG,
		];
	}

	/**
	 * Process file.
	 *
	 * @author Aubrey Portwood
	 *
	 * @param  PHP_CodeSniffer_File $file            The file object.
	 * @param  int                  $doc_block_start Where the docblock starts.
	 *
	 * @since 1.1.0
	 */
	public function process( PHP_CodeSniffer_File $file, $doc_block_start ) {
		$this->tokens = $file->getTokens();
		$token = $this->tokens[ $doc_block_start ];
		$doc_block_end = $token['comment_closer'];

		// The @return in the comment block, false by default.
		$have_an_at_since_tag = false;

		for ( $i = $doc_block_start; $i <= $doc_block_end; $i++ ) {
			if ( stristr( $this->tokens[ $i ]['content'], '@since' ) ) {

				// We found an @return in the block.
				$have_an_at_since_tag = $this->tokens[ $i ];
			}
		}

		if ( ! $have_an_at_since_tag ) {
			$this->error( $file, $doc_block_end, 'Please document the version this was introduced using an @since tag.' );
		}
	}
}