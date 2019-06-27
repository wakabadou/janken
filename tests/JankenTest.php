<?php
/**     ____.              __
 *      |    |____    ____ |  | __ ____   ____
 *     |    \__  \  /    \|  |/ // __ \ /    \
 * /\__|    |/ __ \|   |  \    <\  ___/|   |  \
 * \________(____  /___|  /__|_ \\___  >___|  /
 *               \/     \/     \/    \/     \/
 *
 * @category    Janken
 * @package     Janken
 * @author      akira wakaba <wakabadou@gmail.com>
 * @copyright   2019 - Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/)
 * @license     http://opensource.org/licenses/MIT The MIT License MIT
 * @varsion     1.0.0
 */

declare(strict_types = 1);

namespace Tests;

use App\Janken;
use PHPUnit\Framework\TestCase;

/**
 * Value object: クエリ断片 テスト
 *
 * @runTestsInSeparateProcesses
 */
class JankenTest extends TestCase
{
    //==============================================
    // static methodテスト
    //==============================================
    /**
     * setupのテスト
     *
     * @covers  Janken::setup()
     */
    public function testSetup()
    {
        $this->assertInstanceOf(Janken::class, Janken::setup());
    }

    //==============================================
    // accessor methodテスト
    //==============================================
    /**
     * playerのテスト
     *
     * @covers  Janken::player()
     */
    public function testPlayer()
    {
        //==============================================
        // 正常系のテスト
        //==============================================
        $janken = Janken::setup();

        $this->assertSame(null, $janken->player());

        $this->assertInstanceOf(Janken::class, $janken->player(Janken::HAND_SIGN_CYOKI));
        $this->assertSame(Janken::HAND_SIGN_CYOKI, $janken->player());

        $this->assertInstanceOf(Janken::class, $janken->player(Janken::HAND_SIGN_GU));
        $this->assertSame(Janken::HAND_SIGN_GU, $janken->player());

        $this->assertInstanceOf(Janken::class, $janken->player(Janken::HAND_SIGN_PA));
        $this->assertSame(Janken::HAND_SIGN_PA, $janken->player());

        //==============================================
        // 例外のテスト
        //==============================================
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('使用できない手を渡されました。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');

        $janken->player(-1);

        $janken->player(4);
    }

    /**
     * computerのテスト
     *
     * @covers  Janken::computer()
     */
    public function testcomputer()
    {
        //==============================================
        // 正常系のテスト
        //==============================================
        $janken = Janken::setup();

        $this->assertSame(null, $janken->computer());

        $this->assertInstanceOf(Janken::class, $janken->computer(Janken::HAND_SIGN_CYOKI));
        $this->assertSame(Janken::HAND_SIGN_CYOKI, $janken->computer());

        $this->assertInstanceOf(Janken::class, $janken->computer(Janken::HAND_SIGN_GU));
        $this->assertSame(Janken::HAND_SIGN_GU, $janken->computer());

        $this->assertInstanceOf(Janken::class, $janken->computer(Janken::HAND_SIGN_PA));
        $this->assertSame(Janken::HAND_SIGN_PA, $janken->computer());

        //==============================================
        // 例外のテスト
        //==============================================
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('使用できない手を渡されました。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');

        $janken->computer(-1);

        $janken->computer(4);
    }

    //==============================================
    // __invokeテスト
    //==============================================
    /**
     * __invokeのテスト
     *
     * @covers  Janken::__invoke()
     */
    public function testInvoke()
    {
        //==============================================
        // 正常系のテスト
        //==============================================
        $this->assertSame('あいこ',         Janken::setup()->player(Janken::HAND_SIGN_CYOKI)->computer(Janken::HAND_SIGN_CYOKI)());
        $this->assertSame('計算機の勝ち',   Janken::setup()->player(Janken::HAND_SIGN_CYOKI)->computer(Janken::HAND_SIGN_GU)());
        $this->assertSame('人間の勝ち',     Janken::setup()->player(Janken::HAND_SIGN_CYOKI)->computer(Janken::HAND_SIGN_PA)());

        $this->assertSame('あいこ',         Janken::setup()->player(Janken::HAND_SIGN_GU)->computer(Janken::HAND_SIGN_GU)());
        $this->assertSame('計算機の勝ち',   Janken::setup()->player(Janken::HAND_SIGN_GU)->computer(Janken::HAND_SIGN_PA)());
        $this->assertSame('人間の勝ち',     Janken::setup()->player(Janken::HAND_SIGN_GU)->computer(Janken::HAND_SIGN_CYOKI)());

        $this->assertSame('あいこ',         Janken::setup()->player(Janken::HAND_SIGN_PA)->computer(Janken::HAND_SIGN_PA)());
        $this->assertSame('計算機の勝ち',   Janken::setup()->player(Janken::HAND_SIGN_PA)->computer(Janken::HAND_SIGN_CYOKI)());
        $this->assertSame('人間の勝ち',     Janken::setup()->player(Janken::HAND_SIGN_PA)->computer(Janken::HAND_SIGN_GU)());

        //==============================================
        // 例外のテスト
        //==============================================
        $janken = Janken::setup();

        $this->expectException(\Exception::class);

        //----------------------------------------------
        $this->expectExceptionMessage('人間の手が決まっていません。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');
        //----------------------------------------------
        $janken();

        //----------------------------------------------
        $this->expectExceptionMessage('計算機の手が決まっていません。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');
        //----------------------------------------------
        $janken->player(Janken::HAND_SIGN_CYOKI);
        $janken();

        //----------------------------------------------
        $this->expectExceptionMessage('使用できない手を渡されました。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');
        //----------------------------------------------
        $janken->player(-1);
        $janken->computer(Janken::HAND_SIGN_CYOKI);
        $janken();

        $janken->player(Janken::HAND_SIGN_CYOKI);
        $janken->computer(-1);
        $janken();

        //----------------------------------------------
        // 例外が発生しないこと
        //----------------------------------------------
        $janken->player(Janken::HAND_SIGN_CYOKI);
        $janken->computer(Janken::HAND_SIGN_CYOKI);
        $this->assertSame('あいこ', $janken());
    }
}
