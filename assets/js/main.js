var performace_insight = {
    number: false,
    type: false,
    baseUrl: false,
    loadingImg: $('#loading-img'),
    init: function(baseUrl = false) {
        this.cacheDom();
        this.bindUIActions();
        this.baseUrl = baseUrl;
    },
    cacheDom: function() {
        this.button = $('.apply_margin');
        this.form = $('#frontend-form');
        this.piForm = $('#pi-form');
        this.popUpId = $('#globalModal');
        this.deleteTestUser = $('.delete_test_user');
        this.deleteTestSpace = $('.delete_test_space');
        this.piSubmitButton = $('#admin-test');
        this.performaceSearchBox = $('#performacetestform-url');
        this.performaceResult = $('#performace-result');
        this.siteId = $('#site-id');
        this.pageLoadTime = $('#page-load-time');
        this.totalPageSize = $('#total-page-size');
        this.reportGenerated = $('#total-page-size');
        this.reportGeneratedtime = $('#report-generated-time');
        this.screenShotId = $('#site-screenshot');
        this.performaceSearchKeyword = $('#performacesearchform-keyword');
        this.startTestBtn = $('#perform-auto-search');
        this.piSearchForm = $('#pi-search-form');
        this.searchPerformanceResult = $('#search-performance-result');
        this.modelPopUp = $('#pi-model-popup');
        this.loadingImg = $('#loading-img');
        this.loadingImg2 = $('#loading-img-2');

    },
    bindUIActions: function() {
        this.button.on('click', this.handleButtonClick);
        this.piSubmitButton.on('click', this.handlePiSubmitClick);
        this.startTestBtn.on('click', this.handleStartTest);
        this.deleteTestUser.on('click', this.handleDeleteTestUser);
        this.deleteTestSpace.on('click', this.handleDeleteTestSpace);
    },
    handleButtonClick: function() {

        if (performace_insight.grabDomValues(this) && performace_insight.populateForm()) {
            performace_insight.sendHttpTestRequest(this);
        }
    },
    handleDeleteTestSpace: function() {
        performace_insight.sendHttpDeleteRequest(this, 'space');
    },
    handleDeleteTestUser: function() {
        performace_insight.sendHttpDeleteRequest(this, 'user');
    },
    handlePiSubmitClick: function(e) {
        e.preventDefault();
        performace_insight.sendHttpPerformaceTestRequest(this);
        performace_insight.watchProgress();
    },
    handleStartTest: function(e) {
        e.preventDefault();
        performace_insight.sendHttpSearchPerformaceTestRequest(this);
    },
    grabDomValues: function(ele) {
        this.number = this.convertToInteger($(ele).data('value'));
        this.type = $(ele).data('type');
        return true;
    },
    populateForm: function() {
        this.form.find('#test-type').val(this.type);
        this.form.find('#test-quantity').val(this.number);
        return true;
    },
    convertToInteger: function(value) {
        return parseInt(value);
    },
    beforeTestStart: function(ele) {
        $(ele).attr('disabled', true);
        this.loadingImg.show();

    },
    afterTestSuccess: function(ele) {
        $(ele).attr('disabled', false);
        this.loadingImg.hide();
    },
    sendHttpTestRequest: function(ele) {
        $.ajax({
            type: 'POST',
            url: this.form.attr('action'),
            data: this.form.serialize(),
            success: function(data, textStatus, jQxhr) {
                performace_insight.afterTestSuccess(ele);
                performace_insight.reloadGrid();
                setTimeout(function() {
                    $('.alert-dismissable').hide();
                }, 1000);
            },
            error: function(jqXhr, textStatus, errorThrown) {

            },
            beforeSend: function() {
                performace_insight.beforeTestStart(ele);
            },
        });
    },
    sendHttpDeleteRequest: function(ele, type) {
        $.ajax({
            type: 'POST',
            url: $(ele).data('url'),
            data: {
                type: type
            },
            success: function(data, textStatus, jQxhr) {
                performace_insight.afterTestSuccess(ele);
                performace_insight.reloadGrid();
                setTimeout(function() {
                    $('.alert-dismissable').hide();
                }, 1000);
            },
            error: function(jqXhr, textStatus, errorThrown) {

            },
            beforeSend: function() {
                performace_insight.beforeTestStart(ele);
            },
        });
    },
    sendHttpPerformaceTestRequest: function(ele) {
        $.ajax({
            type: 'POST',
            url: this.piForm.attr('action'),
            data: this.piForm.serialize(),
            dataType: "json",
            success: function(data, textStatus, jQxhr) {
                $('#redirect-message').hide();
                performace_insight.afterTestSuccess(ele);
                clearInterval(interval);
                if (data.success) {
                    performace_insight.displayPerformanceResult(data);
                } else {
                    $('#performace-result').hide();
                    $('#redirect-message').show();
                    setTimeout(function() {
                        $('#redirect-message').hide();
                    }, 3000);
                }


            },
            error: function(jqXhr, textStatus, errorThrown) {

            },
            beforeSend: function() {
                performace_insight.beforeTestStart(ele);
            },
        });

    },
    reloadGrid: function(ele) {
        $.ajax({
            type: 'POST',
            url: this.baseUrl + 'performance_insights/admin/index',
            data: {
                'reload': 1
            },
            success: function(data, textStatus, jQxhr) {
                $('#faker-test-outer').html(data);
            },
            error: function(jqXhr, textStatus, errorThrown) {

            },

        });

    },
    watchProgress: function(ele) {
        interval = setInterval(function() {
                performace_insight.updateProgress();
            },
            1000);
    },
    updateProgress: function(ele) {
        $.ajax({
            type: 'GET',
            url: this.baseUrl + 'performance_insights/admin/current-progress',
            data: this.piForm.serialize(),
            dataType: "json",
            success: function(data, textStatus, jQxhr) {

            },
            error: function(jqXhr, textStatus, errorThrown) {

            },
            beforeSend: function() {

            },
        });

    },
    displayPerformanceResult: function(data) {
        if (data.imgUrl) {
            this.performaceResult.show();
            this.siteId.html(this.performaceSearchBox.val());
            this.animateCounter(data.timeInSec);
            this.totalPageSize.html(data.pageSize);
            this.screenShotId.css('background-image', 'url(' + data.imgUrl + ')');
            this.printCurrentTime();
        } else {
            $.each(data, function(key, val) {
                $('#' + key).next('.help-block').html(val);
            });
        }
    },
    animateCounter: function(input) {
        $('.Count').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: input
            }, {
                duration: 1000,
                easing: 'swing',
                step: function() {
                    $(this).text(Math.ceil(this.Counter * 100) / 100 + ' s');
                }
            });
        });
    },
    printCurrentTime: function() {
        var today = new Date();
        var day = today.getDate();
        var monthIndex = today.getMonth();
        var year = today.getFullYear();
        var monthNames = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];

        h = this.checkTime(today.getHours()),
            m = this.checkTime(today.getMinutes()),
            s = this.checkTime(today.getSeconds());
        this.reportGeneratedtime.html(day + ' ' + monthNames[monthIndex] + ' ' + year + ' ' + h + ":" + m + ":" + s);

    },
    checkTime: function(i) {
        return (i < 10) ? "0" + i : i;
    },
    sendHttpSearchPerformaceTestRequest: function(ele) {
        $.ajax({
            type: 'post',
            url: this.baseUrl + 'performance_insights/admin/test-search',
            data: this.piSearchForm.serialize(),
            success: function(data, textStatus, jQxhr) {
                performace_insight.afterTestSuccess(ele);
                performace_insight.displaySearchOutput(data);
            },
            error: function(jqXhr, textStatus, errorThrown) {

            },
            beforeSend: function() {
                performace_insight.loadingImg = $('#loading-img-2');
                performace_insight.beforeTestStart(ele);
            },
        });
    },
    displaySearchOutput: function(data) {
        if (data.success) {
            this.searchPerformanceResult.find('.ind-item').html(data.output);
            this.animateCounter(data.output);
            this.searchPerformanceResult.show();
        }
    },

}
