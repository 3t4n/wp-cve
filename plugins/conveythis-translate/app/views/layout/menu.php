<?php if (isset($this->variables->api_key)
    && !empty($this->variables->api_key)
    && !empty($this->variables->target_languages)
):?>

    <div class="d-flex align-items-start w-100">
        <ul class="nav nav-tabs mb-3 col" id="pills-tab" role="tablist">

            <?php foreach ($this->variables->menu as $key => $value):

                if($value["status"]):
                    echo '
                                <li class="nav-item" role="presentation">
                                    <button
                                            class="custom-pill nav-link ' . ($value["active"] ? 'active' : '')  . '"
                                            id="' . $value["tag"] . '-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-' . $value["tag"] . '"
                                            type="button" role="tab"
                                            aria-controls="v-pills-' . $value["tag"] . '"
                                            aria-selected="' . ($value["active"] ? 'true' : 'false')  . '"
                                    >
                                        ' . $key . '
                                    </button>
                                </li>
                    ';
                endif;

            endforeach; ?>

            <li class="nav-item" role = "presentation" >
                <button class="custom-pill nav-link" id="cache-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-cache" type="button" role="tab"
                        aria-controls="v-pills-cache" aria-selected="false"> Cache
                </button>
            </li>

        </ul>
    </div>

    <script>


        const tabsContainer = document.querySelector('#pills-tab');
        const menu = <?php echo json_encode($this->variables->menu); ?>;

        function toggleId(event) {
            if (event.target.matches('[id$="-tab"]')) {

                const router = document.querySelector('.router-widget');
                const mainBlock = document.querySelector('.tab-content');
                for(const [key, value] of Object.entries(menu))
                {
                    if(event.target.id === value.tag+'-tab' && !value.widget_preview)
                    {
                        router.style.display = 'none';
                        router.classList.remove('col-md-4');
                        mainBlock.classList.add('col-md-12');
                        mainBlock.classList.remove('col-md-8');
                    }
                    else if(event.target.id === value.tag+'-tab' && value.widget_preview)
                    {
                        router.style.display = 'flex';
                        router.classList.add('col-md-4');
                        mainBlock.classList.add('col-md-8');
                        mainBlock.classList.remove('col-md-12');
                    }
                }

            }
        }

        tabsContainer.addEventListener('click', toggleId);

    </script>

<?php endif; ?>