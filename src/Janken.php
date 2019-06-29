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

namespace App;

/**
 * 人間と計算機でじゃんけんを行います。
 *
 * usage:
 * $janken = Janken::setup();
 * $janken->player(Janken::HAND_SIGN_CYOKI)->computer(Janken::HAND_SIGN_CYOKI);
 * $janken(); // あいこ と表示される
 */
class Janken
{
    /**
     * @var int     じゃんけんの手：ぐー
     */
    public const HAND_SIGN_GU       = 0;

    /**
     * @var int     じゃんけんの手：ちょき
     */
    public const HAND_SIGN_CYOKI    = 1;

    /**
     * @var int     じゃんけんの手：ぱー
     */
    public const HAND_SIGN_PA       = 2;

    // で、なにつくろー、なにつくろー

    /**
     * @var array   じゃんけんの手のマップ
     */
    public const HAND_SIGN_MAP  = [
        self::HAND_SIGN_GU      => self::HAND_SIGN_GU,
        self::HAND_SIGN_CYOKI   => self::HAND_SIGN_CYOKI,
        self::HAND_SIGN_PA      => self::HAND_SIGN_PA,
    ];

    /**
     * @var string  じゃんけんの結果メッセージ：あいこ
     */
    private const RESULT_MESSAGE_AIKO   = 'あいこ';

    /**
     * @var string  じゃんけんの結果メッセージ：勝ち
     */
    private const RESULT_MESSAGE_KATI   = '人間の勝ち';

    /**
     * @var string  じゃんけんの結果メッセージ：負け
     */
    private const RESULT_MESSAGE_MAKE   = '計算機の勝ち';

    /**
     * @var array   じゃんけんの結果メッセージマップ
     */
    private const RESULT_MESSAGE_MAP    = [
        self::HAND_SIGN_GU  => [
            self::HAND_SIGN_GU      => self::RESULT_MESSAGE_AIKO,
            self::HAND_SIGN_CYOKI   => self::RESULT_MESSAGE_KATI,
            self::HAND_SIGN_PA      => self::RESULT_MESSAGE_MAKE,
        ],
        self::HAND_SIGN_CYOKI   => [
            self::HAND_SIGN_GU      => self::RESULT_MESSAGE_MAKE,
            self::HAND_SIGN_CYOKI   => self::RESULT_MESSAGE_AIKO,
            self::HAND_SIGN_PA      => self::RESULT_MESSAGE_KATI,
        ],
        self::HAND_SIGN_PA  => [
            self::HAND_SIGN_GU      => self::RESULT_MESSAGE_KATI,
            self::HAND_SIGN_CYOKI   => self::RESULT_MESSAGE_MAKE,
            self::HAND_SIGN_PA      => self::RESULT_MESSAGE_AIKO,
        ],
    ];

    /**
     * @var null|int    人間の手
     *
     * 未定義状態はnull
     */
    private $player     = null;

    /**
     * @var null|int    計算機の手
     *
     * 未定義状態はnull
     */
    private $computer   = null;

    /**
     * constructor
     */
    private function __construct()
    {
    }

    /**
     * 手を検証します。
     *
     * @param   int         $hand_sign  検証したい手
     * @throws  \Exception  利用できない手が渡された場合。
     */
    private function validateHandSign(int $hand_sign): void
    {
        if (!isset(self::HAND_SIGN_MAP[$hand_sign])) {
            throw new \Exception('使用できない手を渡されました。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');
        }
    }

    /**
     * じゃんけんを実施できるか検証します。
     *
     * @throws  \Exception  人間の手が未定義の場合
     * @throws  \Exception  計算機の手が未定義の場合
     */
    private function validateCanDuel(): void
    {
        $player = $this->player();
        if ($player === null) {
            throw new \Exception('人間の手が決まっていません。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');
        }
        $this->validateHandSign($player);

        $computer   = $this->computer();
        if ($computer === null) {
            throw new \Exception('計算機の手が決まっていません。次のいずれかの手を使用してください。Janken::HAND_SIGN_GU, Janken::HAND_SIGN_CYOKI, Janken::HAND_SIGN_PA');
        }
        $this->validateHandSign($computer);
    }

    /**
     * じゃんけんの準備をします。
     *
     * @return  Janken  このインスタンス
     */
    public static function setup(): Janken
    {
        return new self();
    }

    /**
     * プレイヤーの手を設定・取得します。
     *
     * @param   array       ...$hand_sign   プレイヤーの手
     * @return  int|Janken  プレイヤーの手またはこのインスタンス
     */
    public function player(...$hand_sign)
    {
        if (empty($hand_sign)) {
            return $this->player;
        }
        $hand_sign = $hand_sign[0];
        $this->validateHandSign($hand_sign);
        $this->player = $hand_sign;
        return $this;
    }

    /**
     * 計算機の手を設定・取得します。
     *
     * @param   array       ...$hand_sign   計算機の手
     * @return  int|Janken  計算機の手またはこのインスタンス
     */
    public function computer(...$hand_sign)
    {
        if (empty($hand_sign)) {
            return $this->computer;
        }
        $hand_sign = $hand_sign[0];
        $this->validateHandSign($hand_sign);
        $this->computer = $hand_sign;
        return $this;
    }

    /**
     * じゃんけんを実施し、結果を返します。
     *
     * @return  string  じゃんけんの結果メッセージ
     */
    public function __invoke(): string
    {
        $this->validateCanDuel();
        return self::RESULT_MESSAGE_MAP[$this->player()][$this->computer()];
    }
}
