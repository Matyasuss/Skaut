<body>
    <h1>README – Skautský Bankomatový Systém</h1>
    <p>
        Projekt slouží jako zábavný bankovní systém pro dětské celodenní a delší hry. 
        Umožňuje spravovat herní peníze, které mohou hráči vkládat, vybírat a odesílat. 
        Díky NFC technologii se systém hodí i pro hry využívající fiktivní platební karty.
        Systém podporuje jak tradiční operace s potvrzením bankéře, tak moderní bezkontaktní platby.
    </p>
        <strong>Ukázka projektu:</strong> <a href="https://youtu.be/DkrL1zbBdXI">https://youtu.be/DkrL1zbBdXI</a>
        <strong>Podpora:</strong> Pokud je cílem vytvořit hru pro děti, rád pomohu s kompletním nastavením hry. 
            Kontaktovat mě můžete na emailu: <a href="mailto:matyasuss@gmail.com">matyasuss@gmail.com</a>.
    <h2>Funkce systému</h2>
    <ul>
        <li><strong>Správa účtu hráče:</strong>
            <ul>
                <li>Přihlášení pomocí jména a PINu.</li>
                <li>Možnost vkládat a vybírat herní peníze (vyžaduje potvrzení hráče s rolí bankéře).</li>
                <li>Odesílání herních peněz mezi hráči.</li>
            </ul>
        </li>
        <li><strong>Bezkontaktní platby:</strong>
            <ul>
                <li>Uživatelé mohou platit prostřednictvím NFC.</li>
                <li>Požadavky na použití NFC plateb:
                    <ul>
                        <li>Každý uživatel musí mít vlastní NFC kartu, na kterou lze zapisovat data.</li>
                        <li>Je nutné mít alespoň jeden telefon schopný čtení a zapisování NFC dat.</li>
                    </ul>
                </li>
                <li>Telefon použitý jako terminál (soubor <code>nfc.php</code>) umožňuje:
                    <ul>
                        <li>Nastavit částku.</li>
                        <li>Zvolit přidání nebo odebrání částky.</li>
                        <li>Ověřit dostatečný zůstatek.</li>
                    </ul>
                </li>
                <li>NFC karta obsahuje jednoznačné ID uživatele, které je propojeno s účtem v databázi.</li>
            </ul>
        </li>
        <li><strong>Bankéřské potvrzení:</strong> Bankéř potvrzuje výběry a vklady pomocí hesla: <code>6378591339456</code> (může být změněno dle potřeby).</li>
    </ul>
    <h2>Grafické rozhraní</h2>
    <ul>
        <li>Všechny části systému, kromě <code>nfc.php</code>, jsou graficky uzpůsobeny pro použití na PC (větší obrazovky, pohodlná správa účtů).</li>
        <li>Soubor <code>nfc.php</code> je optimalizován pro mobilní telefony, aby umožnil snadné použití NFC terminálu v terénu.</li>
    </ul>
    <h2>Nastavení databáze</h2>
    <p>Pro správné fungování je potřeba vytvořit MySQL databázi s následující strukturou:</p>
    <pre>
Tabulka: <strong>b_skaut</strong>
- ID: Nenulový int, primární klíč, auto increment (slouží pro identifikaci uživatelů a NFC platby)
- name: Nenulový varchar (pro přihlašování a identifikaci uživatele)
- budget: Nenulový int (aktuální zůstatek hráče)
- pin: Nenulový int (pro přihlašování)
    </pre>
    <h2>Konfigurace databáze</h2>
    <p>V každém PHP souboru je potřeba doplnit přihlašovací údaje k databázi do následující části:</p>
    <pre>
$servername = "";    // Adresa serveru
$db_username = "";   // Uživatelské jméno k databázi
$password = "";      // Heslo k databázi
$dbname = "";        // Název databáze
    </pre>
    <h2>Technologie</h2>
    <ul>
        <li><strong>Frontend:</strong> HTML, CSS, JavaScript</li>
        <li><strong>Backend:</strong> PHP</li>
        <li><strong>Databáze:</strong> MySQL</li>
    </ul>
    <h2>Další informace</h2>
    <ul>
        <li><strong>Licence:</strong> ©Matyasuss 2024. Projekt je určen výhradně pro nekomerční použití.</li>
        <li><strong>Inspirace:</strong> Projekt vznikl jako součást příprav na skautskou akci a může být přizpůsoben jiným událostem.</li>
    </ul>
</body>
