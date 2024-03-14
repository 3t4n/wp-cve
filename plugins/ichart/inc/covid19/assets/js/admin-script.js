! function(n) {
    "use strict";
    n(document).ready(function() {
        t.ready()
    }), n(window).on('load',function() {
        t.load()
    });
    var t = window.$cov_Qcldicp = {
        ready: function() {
            this.qcldicp_site(), this.qcldicp_c(), this.qcldicp_g(), this.qcldicp_t(), this.qcldicp_full()
        },
        load: function() {},
        qcldicp_site: function() {
            n("select[name=covid_country]").on("change", function(t) {
                var e = "",
                    c = n(this).val();
                e = "[QCLDCOVID19-WIDGET", c && (e += ' country="' + c + '" title_widget="' + c + '"'), e += ' confirmed_title="Confirmed" deaths_title="Deaths" recovered_title="Recovered"]', n("#covidsh").html(e)
            })
        },
        qcldicp_c: function() {
            n("select[name=covid_country_line]").on("change", function(t) {
                var e = "",
                    c = n(this).val();
                e = "[QCLDCOVID19-LINE", c && (e += ' country="' + c + '"'), e += ' confirmed_title="confirmed" deaths_title="deaths" recovered_title="recovered"]', n("#covidsh-line").html(e)
            })
        },
        qcldicp_g: function() {
            n("select[name=covid_country_graph]").on("change", function(t) {
                var e = "",
                    c = n(this).val();
                e = "[QCLDCOVID19-GRAPH", c && (e += ' country="' + c + '" title="' + c + '"'), e += ' confirmed_title="Confirmed" deaths_title="Deaths" recovered_title="Recovered"]', n("#covidsh-graph").html(e)
            })
        },
        qcldicp_t: function() {
            n("select[name=covid_country_ticker]").on("change", function(t) {
                var e = "",
                    c = n(this).val();
                e = "[QCLDCOVID19-TICKER", c && (e += ' country="' + c + '" ticker_title="' + c + '"'), e += ' style="vertical" confirmed_title="Confirmed" deaths_title="Deaths" recovered_title="Recovered"]', n("#covidsh-ticker").html(e)
            })
        },
        qcldicp_full: function() {
            n("select[name=covid_country_full]").on("change", function(t) {
                var e = "",
                    c = n(this).val();
                e = "[QCLDCOVID19-WIDGET", c && (e += ' country="' + c + '" title_widget="' + c + '"'), e += ' format="full" confirmed_title="Confirmed" deaths_title="Deaths" recovered_title="Recovered" active_title="Active" today_cases="24h" today_deaths="24h"]', n("#covidsh-full").html(e)
            })
        }
    }
}(jQuery);