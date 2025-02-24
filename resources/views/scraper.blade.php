<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firchun Web Scraper</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>
    {{-- seo --}}
    <meta name="description"
        content="Firchun Web Scraper - Extract data from websites easily with this free online tool. No coding required.">
    <meta name="keywords" content="web scraper, website scraping, data extraction, free web scraper, Laravel scraper">
    <meta name="author" content="Firchun">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Firchun Web Scraper">
    <meta property="og:description"
        content="Extract data from any website with this Firchun Laravel-based web scraper.">
    <meta property="og:image" content="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png">
    <meta property="og:url" href="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Firchun Web Scraper">
    <meta name="twitter:description" content="Extract data from any website easily.">
    <meta name="twitter:image" content="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="icon" href="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png"
        type="image/x-icon">
    <script type="application/ld+json">
          {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "Firchun Web Scraper",
            "url": "{{url('/')}}",
            "author": {
              "@type": "Person",
              "name": "Firchun"
            },
            "description": "Extract data from any website with this Firchun Laravel-based web scraper.",
            "applicationCategory": "Web Scraping"
          }
          </script>
</head>

<body
    class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center transition duration-300">

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 w-full max-w-lg transition duration-300">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Firchun Web Scraper</h2>
            <button id="toggleDarkMode"
                class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-3 py-1 rounded-lg text-sm transition">
                🌙 Dark Mode
            </button>
        </div>

        <form id="scrapeForm" class="flex flex-col space-y-4">
            <input type="url" name="url" id="url" placeholder="Enter website URL"
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white transition"
                required>
            <button type="submit" id="scrapeButton"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                Scrape
            </button>
        </form>

        <div id="loading" class="hidden text-center mt-4 text-gray-800 dark:text-gray-100">
            ⏳ Scraping in progress...
        </div>

        <div id="tabContainer" class="hidden mt-6">
            <div class="flex border-b border-gray-300 dark:border-gray-600">
                <button
                    class="tab-button active-tab w-1/2 py-2 text-center border-b-2 border-blue-500 font-semibold transition"
                    data-tab="json">JSON</button>
                <button
                    class="tab-button w-1/2 py-2 text-center border-b-2 border-transparent hover:border-gray-400 transition"
                    data-tab="python">Python</button>
            </div>

            <pre id="jsonResult"
                class="tab-content bg-gray-200 dark:bg-gray-700 p-4 rounded-lg text-sm overflow-auto max-h-60 mt-2 text-gray-900 dark:text-gray-100"></pre>

            <div id="pythonResult" class="tab-content hidden">
                <button id="copyPython"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-sm mb-2"><i
                        class="bi bi-copy"></i> Copy</button>
                <pre
                    class="bg-gray-200 dark:bg-gray-700 p-4 rounded-lg text-sm overflow-auto max-h-60 mt-2 text-gray-900 dark:text-gray-100"></pre>
            </div>
        </div>

        <button id="download-json"
            class="hidden w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg mt-4 transition">
            Download JSON
        </button>

        <button id="download-python"
            class="hidden w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg mt-4 transition">
            Download Python Script
        </button>

        <button id="download-csv"
            class="hidden w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 rounded-lg mt-4 transition">
            Download CSV
        </button>
    </div>

    <!-- Footer -->
    <footer class="mt-6 text-center text-gray-700 dark:text-gray-300">
        <p class="text-sm">Made with ❤️ by <span class="font-semibold">Firchun</span></p>
        <div class="mt-2 flex justify-center space-x-4">
            <a href="https://github.com/firchun" target="_blank"
                class="hover:text-gray-900 dark:hover:text-white transition text-xl">
                <i class="bi bi-github"></i>
            </a>
            <a href="https://www.instagram.com/firman.id_25/" target="_blank"
                class="hover:text-gray-900 dark:hover:text-white transition text-xl">
                <i class="bi bi-instagram"></i>
            </a>
        </div>
    </footer>
    <script>
        let scrapedData = null;
        let scrapedUrl = "";
        let pythonScript = "";

        function setDarkMode(enabled) {
            const button = document.getElementById("toggleDarkMode");

            if (enabled) {
                document.documentElement.classList.add("dark");
                localStorage.setItem("darkMode", "enabled");
                button.textContent = "☀️ Light Mode";
            } else {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("darkMode", "disabled");
                button.textContent = "🌙 Dark Mode";
            }
        }

        // Toggle Dark Mode ketika tombol diklik
        document.getElementById("toggleDarkMode").addEventListener("click", function() {
            setDarkMode(!document.documentElement.classList.contains("dark"));
        });

        // Setel mode berdasarkan localStorage saat halaman dimuat
        if (localStorage.getItem("darkMode") === "enabled") {
            setDarkMode(true);
        } else {
            setDarkMode(false);
        }

        $("#scrapeForm").submit(function(e) {
            e.preventDefault();
            scrapedUrl = $("#url").val();

            $("#scrapeButton").prop("disabled", true).text("Loading...");
            $("#loading").show();

            $.post("/scrape", {
                url: scrapedUrl,
                _token: "{{ csrf_token() }}"
            }, function(data) {
                scrapedData = data;
                $("#jsonResult").text(JSON.stringify(data, null, 2));

                pythonScript = `import json
import requests
from bs4 import BeautifulSoup

def scrape_website(url):
    headers = {"User-Agent": "Mozilla/5.0"}
    response = requests.get(url, headers=headers)
    response.raise_for_status()
    
    soup = BeautifulSoup(response.text, "html.parser")
    data = {
        "h1": [h1.text.strip() for h1 in soup.find_all("h1")],
        "links": [{"text": a.text.strip(), "href": a["href"]} for a in soup.find_all("a", href=True)]
    }

    with open("scraped_data.json", "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

if __name__ == "__main__":
    scrape_website("${scrapedUrl}")`;

                $("#pythonResult pre").text(pythonScript);

                $("#tabContainer, #jsonResult, #download-json, #download-csv").show();
                $("#pythonResult, #download-python").hide();

                $("#scrapeButton").prop("disabled", false).text("Scrape");
                $("#loading").hide();
            }).fail(function() {
                alert("Failed to scrape. Make sure the URL is valid.");
                $("#scrapeButton").prop("disabled", false).text("Scrape");
                $("#loading").hide();
            });
        });

        $("#download-csv").click(function() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Key,Value\n";
            Object.entries(scrapedData).forEach(([key, value]) => {
                csvContent += `${key},"${JSON.stringify(value)}"\n`;
            });

            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.href = encodedUri;
            link.download = "scraped_data.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        $(".tab-button").click(function() {
            $(".tab-button").removeClass("active-tab border-blue-500");
            $(this).addClass("active-tab border-blue-500");

            $(".tab-content").hide();
            $("#" + $(this).data("tab") + "Result").show();

            $("#download-json").toggle($(this).data("tab") === "json");
            $("#download-python").toggle($(this).data("tab") === "python");
        });
    </script>

</body>

</html>
