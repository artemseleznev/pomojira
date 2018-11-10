<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

require_once "vendor/autoload.php";

use Pomojira\Helper;
Helper::initDotenv();
$issueList = explode(',', $_GET['issues']);
$dataToRender = [];
foreach ($issueList as $issueKey) {
    $api = Helper::getApi();
    $issueData = $api->getIssue($issueKey)->getResult();
    $dataToRender[] = [
        'issueKey' => $issueData['key'],
        'assigneeName' => $issueData['fields']['assignee']['name'],
        'assigneeAvatarLink' => $issueData['fields']['assignee']['avatarUrls']['48x48'],
        'summary' => $issueData['fields']['summary'],
        // можно сделать вывод компонента задачи, если потребуется:
        //'component' => $issueData['fields']['components'][0]['name'],
        'codeReviewer' => $issueData['fields']['customfield_10011']['displayName']
    ];
}
?>
<!DOCTYPE html>
<head>
    <title>Print jira issues</title>
</head>
<link type="text/css" rel="stylesheet"
      href="<?=getenv('JIRA_HOST')?>/s/a3fc6c7eda193d15bf35cbfeecbdd86f-CDN/-1eamx9/75007/e2b820449dba7db3e64153a92911b4be/aa6cf545ff213915ecbf1d6cdcbaa2cc/_/download/contextbatch/css/_super/batch.css"
      data-wrm-key="_super" data-wrm-batch-type="context" media="all">
<link type="text/css" rel="stylesheet"
      href="<?=getenv('JIRA_HOST')?>/s/f6e9358fbf5ece8e8fb6a17329d714f3-CDN/-1eamx9/75007/e2b820449dba7db3e64153a92911b4be/4ab0e2fb52a2d56d6012a4925948cff4/_/download/contextbatch/css/greenhopper-rapid-non-gadget,atl.general,gh-rapid,jira.project.sidebar,com.atlassian.jira.projects.sidebar.init,jira.global,jira.general,-_super/batch.css?agile_global_admin_condition=true&amp;hc-enabled=true&amp;is-server-instance=true&amp;jag=true&amp;jaguser=true&amp;nps-not-opted-out=true&amp;richediton=true&amp;sd_operational=true"
      data-wrm-key="greenhopper-rapid-non-gadget,atl.general,gh-rapid,jira.project.sidebar,com.atlassian.jira.projects.sidebar.init,jira.global,jira.general,-_super"
      data-wrm-batch-type="context" media="all">
<link type="text/css" rel="stylesheet"
      href="<?=getenv('JIRA_HOST')?>/s/32d154aa5ed37a219ff9cd15b0a8a7d1-CDN/-1eamx9/75007/e2b820449dba7db3e64153a92911b4be/95500190d36d26962b40eba9cc2f98e5/_/download/contextbatch/css/gh-rapid-charts,-_super/batch.css"
      data-wrm-key="gh-rapid-charts,-_super" data-wrm-batch-type="context" media="all">
<link type="text/css" rel="stylesheet"
      href="<?=getenv('JIRA_HOST')?>s/b0798f450f70bcb060fee4ed5fc98bf0-T/-1eamx9/75007/e2b820449dba7db3e64153a92911b4be/7.5.2/_/download/batch/com.atlassian.feedback.jira-feedback-plugin:button-resources-init/com.atlassian.feedback.jira-feedback-plugin:button-resources-init.css"
      data-wrm-key="com.atlassian.feedback.jira-feedback-plugin:button-resources-init" data-wrm-batch-type="resource" media="all">
<style type="text/css">
    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }
    }
</style>
<style type="text/css">
    .ghx-print-card-body .ghx-print-large .ghx-card-footer {
        height: 150px;
    }

    .ghx-print-card-body .ghx-card-header .ghx-card-icon, .ghx-print-card-body .ghx-card-header .ghx-card-icon img {
        height: 32px;
        width: 32px;
    }

    .ghx-print-card-body .ghx-print-large .ghx-card {
        height: 100%;
        padding: 20px 10px 20px 35px;
        width: 480px;
    }

    .ghx-card-summary {
        margin-top: 30px !important;
        /*font-size: 35px !important;*/
        line-height: 1em !important;
    }

    img.ghx-avatar-img {
        width: 60px !important;
        height: 60px !important;
    }

    .tester-wrapper {
        width: 180px;
        float: right;
        padding: 0 15px;
        border: 1px dashed #000;
        border-radius: 4px;
        background-color: #fff;
        color: #707070;
    }

    .tester {
        display: inline-block;
        width: 170px;
        margin-left: 18px;
        height: 100%;
        vertical-align: middle;
        margin-top: -5px;
    }

    .tester table {
        border-spacing: 10px 0;
        opacity: 0.6;
    }

    .tester thead {
        line-height: 15px;
        letter-spacing: 4px;
    }

    .tester-text {
        width: 25px;
        height: 33px;
        margin: 10px;
        max-width: 40px;
        overflow: hidden;
        font-size: 11px;
        text-align: center;
        line-height: 11px;
        font-weight: bold;
    }

    .internal {
        color: red;
        border: 2px solid red;
        background-color: #ffd1d1 !important;
    }

    .nothing {
        color: green;
        border: 2px solid green;
        background-color: #d4f0d9;
    }

    .external {
        background-color: #d1d1d1;
        color: #000;
        border: 2px solid #000;
    }
</style>
</head>

<body id="jira" class="ghx-print-card-body">

<button class="pop" onclick="printPage();">Print</button>

<?php foreach ($dataToRender as $item): ?>
    <div class="ghx-print-content-sizing ghx-print-large">
        <div class="ghx-card ">
            <div class="ghx-card-content">
                <div class="ghx-card-header">
                    <div class="ghx-card-key" style="font-size: 48px; font-weight: bold;">
                        <?= $item['issueKey']; ?>
                    </div>
                    <div class="tester">
                        <table style="border-spacing: 10px 0;opacity: 0.6;">
                            <thead>
                            <tr>
                                <th colspan="3">Тестирование</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="tester-text internal">нужно</td>
                                <td class="tester-text nothing">не нужно</td>
                                <td class="tester-text external">внешн</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="ghx-row-end">
                        <img src="<?= $item['assigneeAvatarLink']; ?>" class="ghx-avatar-img"
                             style="border-radius: 25px; height: 100px; line-height: 50px; width: 100px;">
                    </div>
                </div>
                <div class="ghx-card-summary"
                     style="font-size: 32px; line-height: 1.3em; max-height: none; margin-top: 20px !important; font-weight: 100;">
                    <?= $item['summary']; ?>
                    <span class="ellipsis">…</span>
                    <span class="obscurer"> </span>
                </div>
                <div class="ghx-card-extra-fields" style="display: none;">
                    <div class="ghx-card-xfield-row">
                        <div class="ghx-card-xfield-label">Компоненты</div>
                        <div class="ghx-card-xfield-value">Компонент</div>
                    </div>
                    <div class="ghx-card-xfield-row">
                        <div class="ghx-card-xfield-label">Code reviewer</div>
                        <div class="ghx-card-xfield-value"><?= $item['codeReviewer']; ?></div>
                    </div>
                </div>
                <div class="ghx-card-footer">
                </div>
            </div>
            <div class="ghx-card-color" style="border-color:#66cc33;"></div>
        </div>
    </div>
<?php endforeach; ?>

</body>


<script>
    function printPage() {
        console.log(4);
        window.print();

        //workaround for Chrome bug - https://code.google.com/p/chromium/issues/detail?id=141633
        if (window.stop) {
            location.reload(); //triggering unload (e.g. reloading the page) makes the print dialog appear
            window.stop(); //immediately stop reloading
        }
        return false;
    }
</script>
</html>