<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\AnnexAsset::register($this);

?>

<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">

<head>
    <link rel="icon" type="image/png" href=<?=\Yii::$app->request->baseUrl . "/uploads/logo.png"?> />
    <meta charset="<?=Yii::$app->charset?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=Html::csrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <script>
        var baseUrl = "<?=Yii::$app->urlManager->baseUrl?>";
    </script>
    <?php $this->head()?>
</head>

<body class="fixed-left">
    <?php $this->beginBody()?>

    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-block">

                <div class="ex-page-content text-center">
                    <h1 class=""><?=Yii::$app->response->statusCode?>!</h1>
                    <h3 class=""><?=nl2br(Html::encode($message))?></h3><br>

                    <a class="btn btn-danger mb-5 waves-effect waves-light" href="<?=Yii::getAlias("@web")?>">Back to
                        Dashboard</a>
                </div>

            </div>
        </div>
        <?php $this->endBody()?>
</body>

</html>
<?php $this->endPage()?>