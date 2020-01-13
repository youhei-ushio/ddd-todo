<?php

namespace package\Infrastructure\Presenter;

use package\Presentation\CreateTaskPresenter;

final class CreateTaskHtmlRenderer extends HtmlRenderer implements CreateTaskPresenter
{
    public function render(): void
    {
        $html = '
            <html lang="ja">
                <head>
                    <title>タスク作成</title>
                </head>
                <body>
                    <p>タスクを作成しました。</p>
                    <a href="/?action=list">一覧を表示する</a>
                </body>
            </html>
        ';

        $this->_render($html);
    }
}
