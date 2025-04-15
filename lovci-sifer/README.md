<body>
    <h1>Lovci Šifer</h1>
    <p>
      Projekt slouží jako podpora pro jednoduchou hru využívající šifry. 
      Nápad na tuto aplikaci není můj ale vznikl na základě akce JBCCode, kde podobná aplikace byla využívána.
    </p>
        <strong>Ukázka projektu:</strong> <a href="">Již brzy</a> <br>
        <strong>Podpora:</strong> Pokud je cílem vytvořit hru pro děti, rád pomohu s kompletním nastavením hry. 
            Kontaktovat mě můžete na emailu: <a href="mailto:matyasuss@gmail.com">matyasuss@gmail.com</a>.
    <h2>Funkce aplikace</h2>
    <ul>
        <li><strong>Vložení kódu šifry:</strong>
            <ul>
                <li>Hráči napíší kód nacházející se na papírku se šifrou.</li>
                <li>V případě zadání správného kódu se spustí odpočet.</li>
            </ul>
        </li>
        <li><strong>Nápovědy:</strong>
            <ul>
                <li>Po 5 minutách (viz odpočet) se zobrazí první nápověda a odpočet se obnoví</li>
                <li>Po dalších 5 minutách se zobrazí druhá nápověda</li>
                <li>Po dalších 5 minutách se zobrazí řešení šifry (celkem po 15 minutách od zadání kódu)</li>
            </ul>
        </li>
    </ul>
    <h2>Příprava hry</h2>
    <ul>
        <li>Pro hru si přpravíme libovolný počet šifer, ke kterým budeme potřebovat:</li>
          <ul>
            <li>Šifra</li>
            <li>Krátký kód složený z velkých písmen a čísel (kódy se pro danou hru nesmí opakovat)</li>
            <li>Dvě textové nápovědy k vyřešení šifry</li>
            <li>Řešení šifry (řešením by mělo být umístění další šifry)</li>
          </ul>
        <li>Každou šifru a její kód vytiskneme na jednotlivé papíry a rozmístíme v terénu</li>
        <li>Do aplikace vložíme kódy šifer, jejich nápovědy a řešení</li>
        <li>Následně spustíme build a .apk soubor předáme uživatelům (nelze na iOS)</li>
    </ul>
    <h2>Vložení dat</h2>
    <p>Data pro šifry jsou v aplikace v tomto formátu:<br>
    "Kód šifry" to listOf(
                "první nápověda",
                "druhá nápověda",
                "řešení šifry"
            ),</p>
    <h2>Další informace</h2>
    <ul>
        <li><strong>Licence:</strong> ©Matyasuss 2024. Projekt je určen výhradně pro nekomerční použití.</li>
        <li><strong>Inspirace:</strong> Projekt vznikl jako součást příprav na skautskou schůzku a může být přizpůsoben jiným událostem.</li>
    </ul>
</body>

