<?php

namespace nueip\curl;

use Exception;

/**
 * Thead
 *
 * @version  0.0.1
 * @author   Gunter Chou
 *
 * @see      https://github.com/nueip/curl
 */
class Thead
{
    /**
     * 最大進程數
     *
     * @var integer
     */
    protected $childMax = 4;

    /**
     * Setting max running child
     *
     * @param integer $childMax
     * @return Thead
     */
    public function setChildMax($childMax)
    {
        $this->childMax = intval($childMax);
        return $this;
    }

    /**
     * multi process
     *
     * @param  callback $callback
     * @param  array    $arguments
     * @return void
     */
    public function multiProcess($callback, $arguments)
    {
        // 當下進程數
        $childCur = 0;

        // 子進程編號
        $childIndex = 0;

        // 當下最大執行數
        $childMax = $this->childMax;

        do {

            // 檢查是否還有運算資源
            $haveResource = count($arguments);

            if ($haveResource) {
                // 建立新進程前 暫停 5 * childMax ms 防止瞬間大量新增進程
                usleep(5000 * $childMax);

                // 子進程編號
                $childIndex++;

                // 將 當前進程數 加一
                $childCur++;

                // 設定 新進程 執行參數
                $argu = array_shift($arguments);

                // 建立 新進程
                $pid = pcntl_fork();
            } else {
                /*
                 * 無運算資源 時，不建立新進程，
                 * 直接去 父進程區 等待 子進程 結束
                 */
                $pid = true;
            }

            if ($pid || !$haveResource) {
                // 父進程區
                /*
                 * 當 子進程 大於等於 限制上限 或 無運算資源 時，
                 * 等待 一個子進程 結束後才可繼續。
                 */
                if ($childCur >= $childMax || !$haveResource) {

                    // 子進程 狀態
                    $status = null;

                    // 子進程 細項
                    $rusage = null;

                    // 截獲 子進程 退出
                    pcntl_wait($status, 0, $rusage);

                    // 將 當前進程數 減一
                    $childCur--;
                }
            } elseif ($pid === 0) {
                // 子進程區
                call_user_func_array($callback, $argu);
                exit;
            } else {
                // 例外錯誤
                throw new Exception('pcntl_fork failue', 500);
            }

            // 當 子進程 未完全退出 則繼續執行
        } while ($childCur > 0);

        return;
    }
}
