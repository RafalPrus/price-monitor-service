<?php

namespace Tests\Feature\auth;

use App\Models\User;
use App\Services\Allegro\AllegroService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllegroServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function allegro_service_is_returning_price_from_url(): void
    {
        $body = $this->excerptBodyOffer();
        $url = $this->getUrl();
        $service = new AllegroService();

        $canHandle = $service->canHandle($url, $body);
        $fetchedPrice = $service->getOfferPrice();
        $this->assertSame(true, $canHandle);
        $this->assertSame('6,99', $fetchedPrice[0]);
        $this->assertCount(1, $fetchedPrice);



    }

    public function excerptBodyOffer(): string
    {
        return '<div class="mryx_16"><div class="_7030e_qVLm-"><div aria-label="cena 6,99&nbsp;zł" class="mli8_k4 msa3_z4 mqu1_1 mp0t_ji m9qz_yo mgmw_qw mgn2_27 mgn2_30_s mpof_vs munh_8 mp4t_4"><span aria-hidden="true">6,</span><span aria-hidden="true" class="mgn2_19 mgn2_21_s m9qz_yq">99&nbsp;zł</span></div><div class="mpof_vs"><picture><img class="_7030e_bpnv0" src="https://a.allegroimg.com/original/343b4d/ed3f5c04412ab7bd70dd0a34f0cd/brand-subbrand-smart-d8bfa93f10" alt="Allegro Smart!"></picture><div class="mp7g_f6 mjb5_1 mp5q_2t m0ux_fp m0ux_vh_m m31c_d8 mj6k_n7 m9vn_gl" style="transform: translate(-103px, -164px);"><div class="mjyo_6x mjb5_w6 msbw_2 mldj_2 mtag_2 mm2b_2 mgmw_wo msts_n6 tify42 tidkhk mp7g_oh m7er_0k mj6k_96 msa3_ae mp5q_2t m0ux_fp m0ux_vh_m m31c_dl tiuz97 mryx_8 _7030e_F-97Y _7030e_Znd4f"><div class="mg9e_16 mvrt_16 mj7a_16 mh36_16"><p class="mgn2_14 mp0t_0a mqu1_21 mgmw_wo mli8_k4 mp4t_0 m3h2_0 mryx_0 munh_0"><span>Allegro Smart! to <b>darmowe dostawy i zwroty</b>: Kurierem, do Paczkomatów i Punktów odbioru.</span></p></div><div class="mpof_ki m389_6m m7f5_0a mryx_0 mj7a_8"><a class="mgn2_14 mp0t_0a m9qz_yp mp7g_oh mse2_40 mqu1_40 mtsp_ib mli8_k4 mp4t_0 m3h2_0 mryx_0 munh_0 m911_5r mefy_5r mnyp_5r mdwl_5r msbw_2 mldj_2 mtag_2 mm2b_2 mqvr_2 msa3_z4 meqh_en m0qj_5r msts_n7 mh36_16 mvrt_16 mg9e_0 mj7a_0 mjir_sv m2ha_2 m8qd_qh mjt1_n2 b111p mjyo_6x mzmg_6m mj9z_5r mpof_vs mupj_5k mgmw_u5g mrmn_qo mrhf_u8 m31c_kb m0ux_fp b17e3 mqen_m6" href="https://allegro.pl/smart?bi_s=allegro_smart&amp;bi_m=strona_oferty_dymek" data-analytics-clickable="true" data-analytics-click-label="smartPreviewLearnMoreTip" rel="noreferrer noopener" target="_blank">dowiedz się więcej</a></div></div></div></div></div></div>';
    }

    public function getUrl(): string
    {
        return "https://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7";
    }
}
