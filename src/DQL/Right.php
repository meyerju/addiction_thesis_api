<?php

namespace App\DQL;

//use Doctrine\ORM\Query\Node\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use \Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * Description of Quarter
 *
 */
class Right extends FunctionNode
{
    public $column = null;
    public $regexp = null;


    //put your code here
    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('RIGHT(%s, %s)', $this->column->dispatch($sqlWalker), $this->regexp->dispatch($sqlWalker));
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->column = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->regexp = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

}
