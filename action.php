<?php
/**
 * Action plugin to include TikZJax script and styles
 */
class action_plugin_tikzjax extends DokuWiki_Action_Plugin {

    /**
     * Register the events
     *
     * @param Doku_Event_Handler $controller
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'add_tikzjax_headers');
    }

    /**
     * Add TikZJax script and stylesheet to the meta headers
     *
     * @param Doku_Event $event
     * @param mixed $param
     */
    public function add_tikzjax_headers(Doku_Event $event, $param) {
        $event->data['link'][] = [
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => DOKU_BASE . 'lib/plugins/tikzjax/lib/fonts.css'
        ];

        $event->data['script'][] = [
            'type' => 'text/javascript',
            'src' => DOKU_BASE . 'lib/plugins/tikzjax/lib/tikzjax.js',
            '_data' => ''
        ];
    }
}