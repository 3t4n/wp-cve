<div class="modal-vuukle" id="modal-save-settings" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-header" style="border-bottom:1px solid #eee!important">
            <span>Save settings</span>
            <a href="#" class="btn-close" aria-hidden="true">×</a>
        </div>
        <div class="modal-body">
            <p>You have unsaved settings</p>
        </div>
        <div class="modal-footer">
            <a href="#">
                <button type="button" class="button button-secondary" id="vuukle-discart-settings-button">Discard</button>
            </a>
            <button type="button" class="button button-primary" id="vuukle-save-settings-button">Save</button>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector('#vuukle-save-settings-button').addEventListener("click", function () {
            window.location = '#';
            document.querySelector('#vuukle-settings-form').submit();
        });

        document.querySelector('#vuukle-settings-form').addEventListener('change', function () {
            document.querySelector("a:not(#vuukle-settings-form a)").addEventListener('click', function (e) {
                e.preventDefault();
                window.location = '#modal-save-settings';
            });
        });
    });
</script>
<style>
    .modal-header span {
        font-size: 20px;
        font-weight: bold;
        font-family: Arial;
    }

    .btn {
        background: #428bca;
        border: #357ebd solid 1px;
        border-radius: 3px;
        color: #fff;
        display: inline-block;
        font-size: 14px;
        padding: 8px 15px;
        text-decoration: none;
        text-align: center;
        min-width: 60px;
        position: relative;
        transition: color .1s ease;
    }

    .btn:hover {
        background: #357ebd;
    }

    .btn.btn-big {
        font-size: 18px;
        padding: 15px 20px;
        min-width: 100px;
    }

    .btn-close {
        color: #aaa;
        font-size: 30px;
        text-decoration: none;
        position: absolute;
        right: 5px;
        top: 5px;
    }

    .btn-close:hover {
        color: #919191;
    }

    .modal-vuukle:before {
        content: "";
        display: none;
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
    }

    .modal-vuukle:target:before {
        display: block;
    }

    .modal-vuukle:target .modal-dialog {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
        top: 20%;
    }

    .modal-dialog {
        background: #fefefe;
        border: #333 solid 1px;
        border-radius: 5px;
        margin-left: -200px;
        position: fixed;
        left: 50%;
        top: -100%;
        z-index: 11;
        width: 450px;
        -webkit-transform: translate(0, -500%);
        -ms-transform: translate(0, -500%);
        transform: translate(0, -500%);
        -webkit-transition: -webkit-transform 0.3s ease-out;
        -moz-transition: -moz-transform 0.3s ease-out;
        -o-transition: -o-transform 0.3s ease-out;
        transition: transform 0.3s ease-out;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-header,
    .modal-footer {
        padding: 10px 20px;
    }

    .modal-header {
        border-bottom: #eee solid 1px;
    }

    .modal-header h2 {
        font-size: 20px;
    }

    .modal-footer {
        border-top: #eee solid 1px;
        text-align: right;
    }
</style>