<?php

namespace TotalContest\Writers;

use TotalContestVendors\TotalCore\Export\Writer as WriterAbstract;

/**
 * Writer.
 */
abstract class PartialWriter extends WriterAbstract {
	const FIRST_LINE = 'open';
	const LAST_LINE = 'closed';

	protected $line;

	public function markAsFirstLine() {
		$this->line = self::FIRST_LINE;
	}

	public function markAsLastLine() {
		$this->line = self::LAST_LINE;
	}

	public function isFirstLine() {
		return $this->line === self::FIRST_LINE;
	}

	public function isLastLine() {
		return $this->line === self::LAST_LINE;
	}
}
