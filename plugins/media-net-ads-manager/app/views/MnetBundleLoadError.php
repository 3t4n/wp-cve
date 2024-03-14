<div class='error-fallback hide'>
    <img class="image" id='infographic' alt='Error fallback infographic' />
    <h1 class='heading'>Assets loading failed</h1>
    <div class='description'>Slow or no internet connection. Please check your internet connection and try again.</div>
    <div class='action-buttons'>
        <button class='confirm' onclick='reload()'>Reload</button>
    </div>
</div>

<style>
    .confirm-dialog-container-fullscreen.show {
        position: fixed;
        top: 0;
        right: 0;
        left: 20px;
        background: rgba(37, 37, 37, 0.7);
        width: 100%;
        height: 100%;
        overflow: auto;
        z-index: 1000;
    }

    .action-buttons {
        padding: 18px 20px;
        display: flex;
    }

    .error-fallback {
        width: 100%;
        height: 100%;
        min-height: 55vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
    }

    .error-fallback.hide {
        min-height: 0px;
        height: 0px;
        overflow: hidden;
    }

    .error-fallback img {
        max-width: 100%;
        width: 300px;
    }

    .error-fallback .description {
        font-size: 18px;
    }

    .confirm {

        background-color: #4786ff;
        color: #ffffff;
        min-width: 120px;
        box-shadow: 0 3px 9px 0 rgba(71, 134, 255, 0.5);
        height: 40px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        line-height: 17px;
        cursor: pointer;
        border: none;
        padding: 0 20px;
        font: inherit;
    }

    .action-status {
        z-index: 1;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        height: fit-content;
        height: -moz-fit-content;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -60%);
        -moz-transform: translate(-50%, -60%);
        -o-transform: translate(-50%, -60%);
        transform: translate(-50%, -60%);
        min-width: 300px;
        background-color: #fff;
        border-radius: 4px;
        width: fit-content;
        padding: 20px 50px;
    }

    .action-status.hide {
        display: none;
    }

    .action-status.show {
        display: block;
    }

    .action-status .action-buttons {
        padding-bottom: 0;
    }

    .action-status .action-buttons button {
        padding: 0 24px;
    }

    .action-status .flex-center-align {
        align-items: center;
    }

    .action-status .column-flex {
        display: flex;
        flex-direction: column;
    }

    button.confirm {
        margin-right: 0;
        width: auto;
    }


    .action-status .material-icons {
        font-size: 40px;
    }

    .action-status .material-icons.success {
        color: #4cc259;
    }

    .action-status .material-icons.error {
        color: #c74747;
    }

    .action-status .message {
        color: #403b4e;
        font-size: 18px;
        font-weight: 600;
        line-height: 30px;
        margin-top: 10px;
    }
</style>