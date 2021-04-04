<?php

/**
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 */

namespace Wim\Sniffs\ArrowFunction;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowSpaceAfterFnKeywordSniff implements Sniff
{
    /**
     * @return array(int)
     */
    public function register()
    {
        return array(T_FN);
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $nextToken = $tokens[$stackPtr + 1];

        if ($nextToken['content'] !== '(') {
            $error = 'Disallow space after fn';
            $fix = $phpcsFile->addFixableError($error, $stackPtr + 1, 'DisallowSpaceAfterKeyword');

            if ($fix) {
                $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
            }
        }
    }
}
