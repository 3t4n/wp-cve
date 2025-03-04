<?php
/**
 * @license BSD-3-Clause
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\PhpParser\Parser;

/* GENERATED file based on grammar/tokens.y */
final class Tokens
{
    const YYERRTOK = 256;
    const T_THROW = 257;
    const T_INCLUDE = 258;
    const T_INCLUDE_ONCE = 259;
    const T_EVAL = 260;
    const T_REQUIRE = 261;
    const T_REQUIRE_ONCE = 262;
    const T_LOGICAL_OR = 263;
    const T_LOGICAL_XOR = 264;
    const T_LOGICAL_AND = 265;
    const T_PRINT = 266;
    const T_YIELD = 267;
    const T_DOUBLE_ARROW = 268;
    const T_YIELD_FROM = 269;
    const T_PLUS_EQUAL = 270;
    const T_MINUS_EQUAL = 271;
    const T_MUL_EQUAL = 272;
    const T_DIV_EQUAL = 273;
    const T_CONCAT_EQUAL = 274;
    const T_MOD_EQUAL = 275;
    const T_AND_EQUAL = 276;
    const T_OR_EQUAL = 277;
    const T_XOR_EQUAL = 278;
    const T_SL_EQUAL = 279;
    const T_SR_EQUAL = 280;
    const T_POW_EQUAL = 281;
    const T_COALESCE_EQUAL = 282;
    const T_COALESCE = 283;
    const T_BOOLEAN_OR = 284;
    const T_BOOLEAN_AND = 285;
    const T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG = 286;
    const T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG = 287;
    const T_IS_EQUAL = 288;
    const T_IS_NOT_EQUAL = 289;
    const T_IS_IDENTICAL = 290;
    const T_IS_NOT_IDENTICAL = 291;
    const T_SPACESHIP = 292;
    const T_IS_SMALLER_OR_EQUAL = 293;
    const T_IS_GREATER_OR_EQUAL = 294;
    const T_SL = 295;
    const T_SR = 296;
    const T_INSTANCEOF = 297;
    const T_INC = 298;
    const T_DEC = 299;
    const T_INT_CAST = 300;
    const T_DOUBLE_CAST = 301;
    const T_STRING_CAST = 302;
    const T_ARRAY_CAST = 303;
    const T_OBJECT_CAST = 304;
    const T_BOOL_CAST = 305;
    const T_UNSET_CAST = 306;
    const T_POW = 307;
    const T_NEW = 308;
    const T_CLONE = 309;
    const T_EXIT = 310;
    const T_IF = 311;
    const T_ELSEIF = 312;
    const T_ELSE = 313;
    const T_ENDIF = 314;
    const T_LNUMBER = 315;
    const T_DNUMBER = 316;
    const T_STRING = 317;
    const T_STRING_VARNAME = 318;
    const T_VARIABLE = 319;
    const T_NUM_STRING = 320;
    const T_INLINE_HTML = 321;
    const T_ENCAPSED_AND_WHITESPACE = 322;
    const T_CONSTANT_ENCAPSED_STRING = 323;
    const T_ECHO = 324;
    const T_DO = 325;
    const T_WHILE = 326;
    const T_ENDWHILE = 327;
    const T_FOR = 328;
    const T_ENDFOR = 329;
    const T_FOREACH = 330;
    const T_ENDFOREACH = 331;
    const T_DECLARE = 332;
    const T_ENDDECLARE = 333;
    const T_AS = 334;
    const T_SWITCH = 335;
    const T_MATCH = 336;
    const T_ENDSWITCH = 337;
    const T_CASE = 338;
    const T_DEFAULT = 339;
    const T_BREAK = 340;
    const T_CONTINUE = 341;
    const T_GOTO = 342;
    const T_FUNCTION = 343;
    const T_FN = 344;
    const T_CONST = 345;
    const T_RETURN = 346;
    const T_TRY = 347;
    const T_CATCH = 348;
    const T_FINALLY = 349;
    const T_USE = 350;
    const T_INSTEADOF = 351;
    const T_GLOBAL = 352;
    const T_STATIC = 353;
    const T_ABSTRACT = 354;
    const T_FINAL = 355;
    const T_PRIVATE = 356;
    const T_PROTECTED = 357;
    const T_PUBLIC = 358;
    const T_READONLY = 359;
    const T_VAR = 360;
    const T_UNSET = 361;
    const T_ISSET = 362;
    const T_EMPTY = 363;
    const T_HALT_COMPILER = 364;
    const T_CLASS = 365;
    const T_TRAIT = 366;
    const T_INTERFACE = 367;
    const T_ENUM = 368;
    const T_EXTENDS = 369;
    const T_IMPLEMENTS = 370;
    const T_OBJECT_OPERATOR = 371;
    const T_NULLSAFE_OBJECT_OPERATOR = 372;
    const T_LIST = 373;
    const T_ARRAY = 374;
    const T_CALLABLE = 375;
    const T_CLASS_C = 376;
    const T_TRAIT_C = 377;
    const T_METHOD_C = 378;
    const T_FUNC_C = 379;
    const T_LINE = 380;
    const T_FILE = 381;
    const T_START_HEREDOC = 382;
    const T_END_HEREDOC = 383;
    const T_DOLLAR_OPEN_CURLY_BRACES = 384;
    const T_CURLY_OPEN = 385;
    const T_PAAMAYIM_NEKUDOTAYIM = 386;
    const T_NAMESPACE = 387;
    const T_NS_C = 388;
    const T_DIR = 389;
    const T_NS_SEPARATOR = 390;
    const T_ELLIPSIS = 391;
    const T_NAME_FULLY_QUALIFIED = 392;
    const T_NAME_QUALIFIED = 393;
    const T_NAME_RELATIVE = 394;
    const T_ATTRIBUTE = 395;
}
