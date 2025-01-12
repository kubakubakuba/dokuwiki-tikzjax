<?php
/**
 * DokuWiki Plugin TikZJax (Syntax Component)
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Jakub pelc <admin@swpelc.eu>
 */

if (!defined('DOKU_INC')) die();

class syntax_plugin_tikzjax extends DokuWiki_Syntax_Plugin {

    /**
     * Get the type of syntax this plugin defines.
     *
     * @return string 'protected' allows nested content
     */
    public function getType() {
        return 'protected';
    }

    /**
     * Define how this plugin is handled regarding paragraphs.
     *
     * @return string 'block' ensures content is handled as a block element
     */
    public function getPType() {
        return 'block';
    }

    /**
     * Sort order of this plugin
     *
     * @return int
     */
    public function getSort() {
        return 999;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode
     */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<tikzjax>(?=.*?</tikzjax>)', $mode, 'plugin_tikzjax');
    }

    /**
     * Add exit pattern for plugin
     */
    public function postConnect() {
        $this->Lexer->addExitPattern('</tikzjax>', 'plugin_tikzjax');
    }

    /**
     * Handle matches of the plugin.
     *
     * @param string $match
     * @param int $state
     * @param int $pos
     * @param Doku_Handler $handler
     * @return array
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                return ['state' => 'enter'];
            case DOKU_LEXER_UNMATCHED:
                return ['state' => 'content', 'content' => $match];
            case DOKU_LEXER_EXIT:
                return ['state' => 'exit'];
        }
        return [];
    }

    /**
     * Render the output
     *
     * @param string $mode
     * @param Doku_Renderer $renderer
     * @param array $data
     * @return bool
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;

        if ($data['state'] === 'enter') {
            $renderer->doc .= '<script type="text/tikz">';
        } elseif ($data['state'] === 'content') {
            $renderer->doc .= html_entity_decode(htmlspecialchars($data['content']));
        } elseif ($data['state'] === 'exit') {
            $renderer->doc .= '</script>';
            // Add inline style to increase rendered SVG size
            $renderer->doc .= '<style>
                svg { width: 100%; height: auto; }
            </style>';
        }

        return true;
    }
}