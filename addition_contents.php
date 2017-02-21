<?php
/**
 * Addition Contents plugin.
 *
 * It allows add add manage addition contents on page.
 *
 * @author  Prakai Nadee <prakai@rmuti.acth>
 * @version 1.0.0
 */
defined('INC_ROOT') OR die('Direct access is not allowed.');

wCMS::addListener('js', 'loadAdditionContentsJS');
wCMS::addListener('css', 'loadAdditionContentsCSS');
wCMS::addListener('editable', 'loadAdditionContentsEditable');

function loadAdditionContentsJS($args) {

    $script = <<<'EOT'

<script src="plugins/addition_contents/js/script.js"></script>
EOT;
	array_push($args[0], $script);
	return $args;
}

function loadAdditionContentsCSS($args) {

	$script = <<<'EOT'
<link rel="stylesheet" href="plugins/addition_contents/css/style.css" type="text/css" media="screen" charset="utf-8">
EOT;
	array_push($args[0], $script);
	return $args;
}

function loadAdditionContentsEditable($contents) {
    $content = $contents[0];
    $subside = $contents[1];

    $page = mb_strtolower(wCMS::$_currentPage);
    wCMS::$currentPage = $page;

    if (wCMS::$loggedIn) {
        if ( ! is_null(wCMS::p('del_ac'))) {
            $key = wCMS::p('del_ac');
            $db = json_decode(wCMS::db());
            if (isset($db->pages->$page->$key)) {
                list($_, $k) = explode('content_', $key);
                unset($db->pages->$page->$key);
                for ($i=$k+1 ;$i!=0; $i++) {
                    $addition_content = getContent('addition_content_'.$i);
                    $addition_content_show = (getContent('addition_content_show_'.$i)=='hide') ? 'hide':'show';
                    if (empty($addition_content)) {
                        break;
                    }
                    $key = 'addition_content_'.$i;
                    unset($db->pages->$page->$key);
                    $key = 'addition_content_'.$k;
                    $db->pages->$page->$key = $addition_content;
                    $key = 'addition_content_show_'.$i;
                    unset($db->pages->$page->$key);
                    $key = 'addition_content_show_'.$k;
                    $db->pages->$page->$key = $addition_content_show;
                    $k++;
                }
                wCMS::pushContents($db);
                sleep(2);
            }
            die;
        }
        if ( ! is_null(wCMS::p('add_ac'))) {
            $key = wCMS::p('add_ac');
            $content = wCMS::p('content');
            $db = json_decode(wCMS::db());
            $db->pages->$page->$key = $content;
            list($_, $k) = explode('content_', $key);
            $bf_addition_content = getContent('addition_content_'.$k);
            $bf_addition_content_show = (getContent('addition_content_show_'.$k)=='hide') ? 'hide':'show';
            if (!empty($bf_addition_content)) {
                for ($i=$k+1 ;$i!=0; $i++) {
                    $addition_content = getContent('addition_content_'.$i);
                    $addition_content_show = (getContent('addition_content_show_'.$i)=='hide') ? 'hide':'show';
                    $key = 'addition_content_'.$i;
                    $db->pages->$page->$key = $bf_addition_content;
                    $key = 'addition_content_show_'.$i;
                    $db->pages->$page->$key = $bf_addition_content_show;
                    if (empty($addition_content)) {
                        break;
                    }
                    $bf_addition_content = $addition_content;
                    $bf_addition_content_show = $addition_content_show;
                    $k++;
                }
            }
            $key = 'addition_content_1';
            $db->pages->$page->$key = $content;
            $key = 'addition_content_show_1';
            $db->pages->$page->$key = 'hide';
            wCMS::pushContents($db);
            sleep(2);
            die;
        }
        $content = '<div id="contents"  class="addition_contents">'.$content;
        $content.='
        <ul class="nav navbar-left"><li><i value="1" class="btn glyphicon glyphicon-plus-sign content_plus" data-toggle="tooltip" title="Add a content"></i></li></ul><br style="font-size: 1.1em;"/>';
        for ($i=1; $i!=0; $i++) {
            $addition_content = getContent('addition_content_'.$i);
            if (empty($addition_content)) {
                break;
            }
            $content.='<p></p>';
            $addition_content_show = getContent('addition_content_show_'.$i);
            $addition_content_show = ($addition_content_show) ? $addition_content_show:'show';
            $content.='
            <ul class="nav navbar-left addition_content">
            <li>';
            if ($addition_content_show=='show') {
                $content.='
                <i value="'.$i.'" class="btn glyphicon glyphicon-eye-open toolbar content_hide" data-toggle="tooltip" title="Hide content"></i>';
            } else {
                $content.='
                <i value="'.$i.'" class="btn glyphicon glyphicon-eye-close toolbar content_show" data-toggle="tooltip" title="Show content"></i>';
            }
            $content.='
            <i value="'.$i.'" class="btn glyphicon glyphicon-minus-sign toolbar content_delete" data-toggle="tooltip" title="Remove content"></i>
            </li>
            </ul>';
            $content.= '
            <hr />';
            $content.= $addition_content = wCMS::editable('addition_content_'.$i, $addition_content);
        }
        $content.= '</div>';
    } else {
        $content = '<div id="content">'.$content.'</div>';
        for ($i=1; $i!=0; $i++) {
            $addition_content = getContent('addition_content_'.$i);
            if (empty($addition_content)) {
                break;
            }
            $addition_content_show = getContent('addition_content_show_'.$i);
            $addition_content_show = ($addition_content_show) ? $addition_content_show:'show';
            if ($addition_content_show=='show')
                $content.='<hr /><div id="addition_content_'.$i.'">'.$addition_content.'</div>';
        }
    }
    return array($content, $subside);
}

function getContent($key, $page = false)
{
    if ( ! $page) $page = mb_strtolower(wCMS::$_currentPage);
    return isset(json_decode(wCMS::db())->pages->$page->$key) ? json_decode(wCMS::db())->pages->$page->$key : false;
}
