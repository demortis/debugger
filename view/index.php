<?php

/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 10.09.18
 * Time: 8:48
 */
?>

<!--<link  rel="stylesheet" href="https://4.kolesa-darom.ru/--><?//=basename(dirname(__DIR__))?><!--/assets/style.css">--> <!-- TODO убрать каку -->
<link  rel="stylesheet" href="assets/style.css">
<link  rel="stylesheet" href="assets/jspanel/jspanel.css">
<!--    <div id="debug-control-panel">-->
<!--        <div id="debug-panel-resize"></div>-->
<!--        <div class="header">Debug panel</div>-->
<!--        <a href="#" data-target="minimized">_</a>-->
<!--        <a href="#" data-target="">&square;</a>-->
<!--        <a href="#" data-target="closed">&times;</a>-->
<!--    </div>-->

<div id="debug-panel" class="">
    <div id="debug-panel-tabs">
        <?php foreach ($submodules as $name => $module): ?>
            <a href="#" class="active"><?=strtoupper($name)?></a>
        <?php endforeach; ?>
    </div>
    <div id="debug-panel-content">
        <?php foreach ($submodules as $name => $module): ?>
            <div class="debug-panel-content-tab">
                <?php if (isset($debugger->exceptions[$name])): ?>
                    <div id="debug-exception">
                        <p class="message"><i>!</i>Модуль не удалось инициализировать!</p>
                        <?php foreach ($debugger->exceptions[$name] as $exception): ?>
                        <div class="exception-row">
                            <a href="#">
                                <div>
                                    <strong><?=$exception->getName()?></strong>
                                    in file
                                    <strong><?=$exception->getFile()?></strong>
                                    on line
                                    <strong><?=$exception->getLine()?></strong>
                                </div>
                                <span>Click for stack trace</span>
                            </a>
                            <input type="checkbox" name="trace">
                            <div class="exception-stack-trace">
                                <?php foreach ($exception->getTrace() as $call): ?>
                                    <div class="exception-stack-trace-row">
                                        <p><?= $call['file'] ?>:<strong><?=$call['line']?></strong> <span style="color:#ff4845"><?=$call['class']?> <strong><?=$call['type']?></strong> <?=$call['function']?></</span></p>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <?=$module::getPanel()?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!--<script src="https://4.kolesa-darom.ru/--><?//=basename(dirname(__DIR__))?><!--/assets/script.js"></script>--> <!-- TODO убрать каку -->
<script src="assets/jspanel/jspanel.js"></script>
<script src="assets/script.js"></script>

