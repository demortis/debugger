<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 11.09.18
 * Time: 14:13
 */
?>
<link rel="stylesheet" href="<?=$module->getAssetsPath()?>/style.css">
<table>
    <thead>
    <tr class="filter">
        <td colspan="4">
            <?php foreach ($counters as $name => $counter): ?>
                <a href="#" data-target-query="<?= $name ?>" class="button button-<?= $name ?> <?= $name === 'all' ? 'active' : ''?>"><?= strtoupper($name) ?> (<?= $counter ?>)</a>
            <?php endforeach; ?>
        </td>
    </tr>
    <tr>
        <th data-action="sort">№</th>
        <th>Query</th>
        <th data-action="sort">Return rows</th>
        <th data-action="sort">Time</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($queries)): ?>
        <tr><td colspan="4">No queries</td></tr>
    <?php else: ?>
        <?php foreach ($queries as $id => $query): ?>
            <tr data-query-type="<?= $query->getSqlQueryParser()->getQueryType() ?>">
                <td><?= ++$id ?></td>
                <td class="query">
                    <span class="mark"></span>
                    <?= $query->query_string ?>
                    <?= $query->getException() ? '<a href="#" class="query-error" data-exception-name="'.get_class($query->getException()).'" data-exception-text="'.$query->getException()->getName().'"></a>' : ''?>
                    <!--                            <a href="#" class="button">Explain</a>-->
                    <!--                            <div>-->
                    <!--                            </div>-->
                </td>
                <td><?= $query->result->num_rows ?? '' ?></td>
                <td><?= $query->execution_time ?> ms</td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<script src="<?=$module->getAssetsPath()?>/script.js"></script>
