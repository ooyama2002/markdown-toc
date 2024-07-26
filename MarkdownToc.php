<?php

/**
 * MarkdownToc
 * https://github.com/ooyama2002/markdown-toc
 * (c) ooyama2002@gmail.com
 */
class MarkdownToc
{
    private $tocArray;

    private $spanTocId;

    public function makeToc($html)
    {

        $this->initParameter();

        $ret = $this->replaceHeading($html);

        $ret = $this->getToc() . $ret;

        return $ret;
    }

    private function initParameter()
    {
        $this->tocArray = array();
        $this->spanTocId = "span_toc_" . md5(get_class($this));
    }

    private function replaceHeading($html)
    {
        $ret = $html;

        // Replace heading
        $ret = preg_replace_callback('/(<(h[1-6]).*?>)(.*?)(<\/\2>)/is', function ($m1) {

            // ex.
            // [0] => <h2 class="test1-5" id="case1-5">ｈｅａｄｉｎｇ１－５</h2>
            // [1] => <h2 class="test1-5" id="case1-5">
            // [2] => h2
            // [3] => ｈｅａｄｉｎｇ１－５
            // [4] => </h2>

            $textContent = $m1[3];
            $id = $m1[2] . "_" . (count($this->tocArray) + 1) . "_" . md5($textContent);

            $this->tocArray[] = [intval(substr($m1[2], 1)), $id, $textContent];

            $tagS = $m1[1];
            $tagE = $m1[4];

            // Add id attribute
            if (preg_match('/(id\s*?=\s*?["\'])(.*?)(["\'])/is', $tagS, $m2)) {
                // Replace id attribute if it exists
                $tagS = preg_replace_callback('/(id\s*?=\s*?["\'])(.*?)(["\'])/is', function ($m2) {

                    // ex. <h2 class="test1-5" id="case1-5">
                    // [0] => id="case1-5"
                    // [1] => id="
                    // [2] => case1-5
                    // [3] => "
                    list($tmp1, $tmp2, $tmp3) = $this->tocArray[count($this->tocArray) - 1];
                    return $m2[1] . $tmp2 . $m2[3];
                }, $tagS);
            } else {
                // Add id attribute if id attribute does not exist
                $tagS = substr($tagS, 0, strlen($tagS) - 1) . " id=\"" . $id . "\">";
            }

            // Provide a link to navigate to the table of contents
            return $tagS . $textContent . " <a href=\"#" . $this->spanTocId . "\">↑</a>" . $tagE;
        }, $ret);

        return $ret;
    }

    private function getToc()
    {
        $ret = "<span id=\"" . $this->spanTocId . "\"></span>\n";

        $currlevel = 1;
        $ret .= "<ul>\n";
        foreach ($this->tocArray as $tmpArray) {
            list($level, $id, $text) = $tmpArray;
            if ($currlevel < $level) {
                while ($currlevel < $level) {
                    $ret .= "<ul>\n";
                    $currlevel++;
                }
            } else if ($currlevel > $level) {
                while ($currlevel > $level) {
                    $ret .= "</ul>\n";
                    $currlevel--;
                }
            }

            $ret .= "<li><a href=\"#" . $id . "\">" . $text . "</a></li>\n";
        }

        while ($currlevel > 1) {
            $ret .= "</ul>\n";
            $currlevel--;
        }

        $ret .= "</ul>\n";

        return $ret;
    }
}
?>