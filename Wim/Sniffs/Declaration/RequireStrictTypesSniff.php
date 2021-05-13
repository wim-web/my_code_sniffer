<?php

/**
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 */

namespace Wim\Sniffs\Declaration;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireStrictTypesSniff implements Sniff
{

    /**
     * @return array(int)
     */
    public function register()
    {
        return [T_OPEN_TAG];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $declare = $phpcsFile->findNext(T_DECLARE, $stackPtr);
        $found   = false;

        if ($declare !== false) {
            $nextString = $phpcsFile->findNext(T_STRING, $declare);

            if ($nextString !== false) {
                if (strtolower($tokens[$nextString]['content']) === 'strict_types') {
                    // There is a strict types declaration.
                    $found = true;
                }
            }
        }

        if ($found === false) {
            $error = 'Missing required strict_types declaration';
            $namespacePtr = $phpcsFile->findNext(T_NAMESPACE, $stackPtr + 1);

            if ($namespacePtr === false) {
                $phpcsFile->addError($error, $stackPtr, 'MissingDeclaration');
            } else {
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'MissingDeclaration');

                if ($fix) {

                    $whiteSpaceBeforeNamespaceCount = 0;

                    for ($i = $namespacePtr - 1; $i > 0; $i--) {
                        if ($tokens[$i]['type'] === "T_WHITESPACE") {
                            $whiteSpaceBeforeNamespaceCount++;
                        } else {
                            break;
                        }
                    }

                    $newLineInGap = $whiteSpaceBeforeNamespaceCount - 1;
                    $exceptNewLine = 1;
                    $diff = $newLineInGap - $exceptNewLine;
                    
                    if ($diff > 0) {
                        for ($i = 0; $i < $diff; $i++) {
                            $phpcsFile->fixer->revertToken($namespacePtr - 1);
                        }
                    }

                    if ($diff < 0) {
                        for ($i = 0; $i < abs($diff); $i++) {
                            $phpcsFile->fixer->addNewlineBefore($namespacePtr);
                        }
                    }
                    
                    $phpcsFile->fixer->addContentBefore($namespacePtr, "declare(strict_types=1);" . PHP_EOL . PHP_EOL);
                }
            }
        }

        return $phpcsFile->numTokens;
    }
}
