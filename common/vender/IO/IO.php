<?php
namespace luoyy\IO;

/**
 * Download
 */
class IO
{
    public static function dowload($path, $filename = '')
    {
        $size = filesize($path);
        (isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE']) && $range = substr($_SERVER['HTTP_RANGE'], 6)) || $range = '0-' . ($size - 1);
        if (substr($range, -1) == '-') {
            $init = substr($range, 0, -1);
            $stop = $size - 1;
        } elseif (substr($range, 0, 1) == '-') {
            $init = $size - substr($range, 1) - 1;
            $stop = $size - 1;
        } else {
            $init_stop = explode('-', $range);
            $init = $init_stop[0];
            $stop = $init_stop[1];
        }
        if (isset($_SERVER['HTTP_RANGE'])) {
            header('HTTP/1.1 206 Partial Content');
        }
        header('Accept-Ranges: bytes');
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename=' . (!empty($filename) ? $filename : basename($path)));
        header("Content-Range: bytes $init-$stop/$size");
        header('Content-Length: ' . ($stop - $init + 1));
        $fp = fopen($path, "rb");
        fseek($fp, $init);
        while (!feof($fp)) {
            echo fread($fp, 4096);
            if (ftell($fp) > $stop) {
                break;
            }
        }
        fclose($fp);
    }
}
