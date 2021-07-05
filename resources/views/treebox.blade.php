<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Treebox</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <script nonce="203d512510cd4ed08e11214435b" src="//local.adguard.org?ts=1625202806228&amp;type=content-script&amp;dmn=bl.ocks.org&amp;app=chrome.exe&amp;css=1&amp;js=1&amp;gcss=1&amp;rel=1&amp;rji=1&amp;sbe=0"></script>
        <script nonce="203d512510cd4ed08e11214435b" src="//local.adguard.org?ts=1625202806228&amp;name=AdGuard%20Popup%20Blocker&amp;name=AdGuard%20Extra&amp;type=user-script"></script>
        <link rel="stylesheet" type="text/css" href="<?= asset('treebox/tree-boxes.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset('treebox/bootstrap.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset('treebox/bootstrap-daterangepicker/daterangepicker.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset('fontawesome/css/font-awesome.css') ?>">

        <script src="<?= asset('treebox/moment.min.js') ?>"></script>
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script src="https://d3js.org/d3.v3.min.js"></script>
        <script src="<?= asset('treebox/tree-boxes.js') ?>"></script>
        <script src="<?= asset('treebox/bootstrap-daterangepicker/daterangepicker.min.js') ?>"></script>
    </head>
    <body>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-info" role="alert">
                It might take long to take data from database. Wait a little...
            </div>
            <form method="get" class="form-horizontal" style="margin-top: 20px">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="mone_av">Choose parent Mone:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="mone_av" name="mone_av">
                                    <option value="0">__ROOT__</option>
                                    @foreach($parent_mones_arr as $each)
                                    <option value="<?= $each['mone'] ?>" @if ($mone_av == $each['mone']) selected @endif>
                                        <?= $each['mone'] . ($each->hisCustomer ? ' ('.$each->hisCustomer->address.')' : '') ?>
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-3">Date Range</label>
                            <div class="col-md-9">
                                <div class="input-group" id="defaultrange">
                                    <input type="text" class="form-control" name="daterange" value="">
                                    <span class="input-group-btn">
                                        <button class="btn grey date-range-toggle" type="button" style="line-height: 1.5;">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <ct-visualization id="tree-container"></ct-visualization>
        </div>
        <script>
            var data = JSON.parse(atob('<?= $mones ?>'));
            console.log(data);
            treeBoxes('',data);
            // $(document).ready(function() {
            //     $("#defaultrange").daterangepicker({
            //         format: "YYYY-MM-DD",
            //         separator: " to ",
            //         startDate: moment().subtract("days", 29),
            //         endDate: moment(),
            //         ranges: {
            //             Today: [moment(), moment()],
            //             Yesterday: [moment().subtract("days", 1), moment().subtract("days", 1)],
            //             "Last 7 Days": [moment().subtract("days", 6), moment()],
            //             "Last 30 Days": [moment().subtract("days", 29), moment()],
            //             "This Month": [moment().startOf("month"), moment().endOf("month")],
            //             "Last Month": [moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]
            //         }
            //     }, function(t, e) {
            //         $("#defaultrange input").val(t.format("YYYY-MM-DD") + "~" + e.format("YYYY-MM-DD"))
            //     })
            // })
        </script>
    </body>
</html>
