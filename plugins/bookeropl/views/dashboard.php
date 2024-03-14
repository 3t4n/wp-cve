<div class="wrap">
    <?php
    $active = 'dashboard';
    include_once 'partials/tabs.php';
    ?>
    <h2 class='opt-title'>
        Kalendarz rezerwacji
    </h2>
    <?php
    if (!empty($this->options['bookero_api_key']) && Bookero::checkApiKey($this->options['bookero_api_key']) !== false):
    ?>
    <p>
        <?php
        $waiting = count(Bookero::getInquiries(1));
        if($waiting):
        ?>
        <span style="color: #ce2720"><strong>Rezerwacje oczekujące na odpowiedź: </strong> <?php echo $waiting; ?></span><br />
        <?php
        endif;
        ?>
        <strong>Potwierdzone rezerwacje na dzisiaj: </strong> <?php echo count(Bookero::getInquiries(3,1)); ?>
    </p>
    <p>
        Kliknij przycisk poniżej i przejdź do pełnego kalendarza rezerwacji:
    </p>
    <?php
    elseif(!empty($this->options['bookero_api_key'])):
    ?>
    <p>
        Podany klucz API jest niepoprawny. Przejdź do <a href="?page=bookero-settings">ustawień</a> i wpisz poprawny <strong>klucz API</strong>.<br />
        <strong>Nie masz jeszcze konta Bookero ?</strong> Zarejestruj się <a href="https://www.bookero.pl" target="_blank">tutaj</a> i przetestuj nasz system rezerwacji online za darmo.
    </p>
    <?php
    else:
    ?>
    <p>
        Aby móc korzystać z systemu rezerwacji, przejdź do <a href="?page=bookero-settings">ustawień</a> i wpisz <strong>klucz API</strong>.<br />
        <strong>Nie masz jeszcze konta Bookero ?</strong> Zarejestruj się <a href="https://www.bookero.pl" target="_blank">tutaj</a> i przetestuj nasz system rezerwacji online za darmo.
    </p>
    <?php
    endif;
    ?>
</div>