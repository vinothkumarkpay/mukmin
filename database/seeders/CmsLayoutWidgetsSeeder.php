<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\Widget;
use Illuminate\Database\Seeder;

/**
 * Seeds CMS page widgets (zone "cms") using settings.cms_layout Blade partials.
 * Copy aligns to the MUKMIN website deck (26.4.26_r4).
 *
 * PortalSeeder only invokes this when SEED_CMS_LAYOUT_WIDGETS=true in .env, so db:seed
 * does not overwrite edited widgets by default. To (re)apply this template anytime:
 * php artisan db:seed --class=CmsLayoutWidgetsSeeder
 */
class CmsLayoutWidgetsSeeder extends Seeder
{
    public function run(): void
    {
        $pid = static function (string $slug): int {
            $id = CmsPage::query()->where('slug', $slug)->value('id');
            if (! $id) {
                throw new \RuntimeException("CMS page not found: {$slug}. Run PortalSeeder page seeds first.");
            }

            return (int) $id;
        };

        $mflsDetail = <<<'HTML'
<h3>2025/2026 Intake Overview</h3>
<ul>
<li>RM5,000,000++ confirmed scholarship pool</li>
<li>6+ partner institutions</li>
<li>50+ scholarship placements available</li>
</ul>
<h3>How It Works</h3>
<ol>
<li>Submit your MFLS application</li>
<li>Shortlisting and joint selection with university partners</li>
<li>Successful candidates receive an offer from the institution</li>
</ol>
<h3>Available Pathways</h3>
<p>MFLS offers both academic and skills-based pathways, ensuring multiple routes to success based on each student&rsquo;s strengths and aspirations.</p>
<h4>Academic Pathways</h4>
<p>Structured programmes from Foundation to Master level, designed to develop strong academic grounding, industry-relevant knowledge, and leadership and professional capabilities.</p>
<p><strong>Programme areas include:</strong> Business &amp; Management (e.g. Business Administration, International Business); Accounting, Finance &amp; Islamic Finance; Law (including international / transfer pathways); Information Technology &amp; Computer Science (e.g. Software Engineering, Data Analytics); Health Sciences (e.g. Nursing, Biomedical Science, Public Health); Psychology &amp; Social Sciences; Hospitality &amp; Tourism Management; Media, Communication &amp; Digital Marketing.</p>
<h4>TVET (Skills &amp; Technical Pathways)</h4>
<p>Industry-relevant, hands-on programmes designed to support immediate employability, technical skill mastery, and entrepreneurship pathways.</p>
<p><strong>Programme areas include:</strong> Hospitality &amp; Culinary Arts; Digital Skills / IT / Coding; Business &amp; Retail Operations; Creative Media.</p>
HTML;

        Widget::query()->updateOrCreate(
            ['slug' => 'about-mukmin-lead'],
            [
                'title' => 'About overview',
                'zone' => 'cms',
                'cms_page_id' => $pid('about-mukmin'),
                'content' => '<p>Explore the sections below to understand our identity, our people, and how the MUKMIN ecosystem works in practice.</p>',
                'settings' => [
                    'cms_layout' => 'lead',
                    'image_url' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&h=800&q=80',
                    'eyebrow' => 'About MUKMIN',
                    'headline' => 'One movement for inclusive, sustainable communities',
                    'subheadline' => 'MUKMIN connects organisations, institutions, and grassroots networks across Malaysia — aligning action and scaling impact.',
                    'ctas' => [
                        ['label' => 'Who We Are', 'url' => '/page/who-we-are', 'style' => 'primary', 'new_tab' => false],
                        ['label' => 'The Team', 'url' => '/page/the-team', 'style' => 'ghost', 'new_tab' => false],
                        ['label' => 'Explore Our Ecosystem', 'url' => '/page/our-ecosystem', 'style' => 'ghost', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'who-we-are-lead'],
            [
                'title' => 'Who We Are',
                'zone' => 'cms',
                'cms_page_id' => $pid('who-we-are'),
                'content' => <<<'HTML'
<p>MUKMIN is a national coordinating ecosystem that brings together NGOs, civil society organisations, chambers of commerce, institutions, global think tanks and impact organisations, alongside grassroots community networks including surau, madrasah and mosques across Malaysia, under a shared mission to advance inclusive and sustainable socio-economic development.</p>
<p>More than a platform, MUKMIN connects communities, aligns stakeholders, and catalyses collective action — transforming community-driven efforts into scalable, high-impact initiatives that drive long-term, sustainable change.</p>
HTML
                ,
                'settings' => [
                    'cms_layout' => 'lead',
                    'image_url' => 'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?auto=format&fit=crop&w=1400&h=500&q=80',
                    'eyebrow' => 'Who We Are',
                    'headline' => 'A National Ecosystem for Community Transformation',
                    'subheadline' => 'Uniting communities, organisations, and partners to drive inclusive and sustainable socio-economic development across Malaysia.',
                    'ctas' => [
                        ['label' => 'Explore Our Ecosystem', 'url' => '/page/our-ecosystem', 'style' => 'primary', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'the-team-intro'],
            [
                'title' => 'The Team',
                'zone' => 'cms',
                'cms_page_id' => $pid('the-team'),
                'content' => '<p>MUKMIN is driven by a diverse group of leaders, practitioners, and collaborators who bring together expertise across community development, policy, education, and industry. United by a shared purpose, the team works collectively to design, mobilise, and deliver initiatives that create meaningful and lasting impact.</p>',
                'settings' => [
                    'cms_layout' => 'team_intro',
                    'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1400&h=500&q=80',
                    'headline' => 'People Behind the Mission',
                    'subheadline' => 'A dedicated team committed to advancing communities through collaboration, leadership, and purposeful action.',
                    'groups' => [
                        [
                            'title' => 'Council of Advisors (COA)',
                            'subtitle' => 'Guiding vision, strategy, and partnerships',
                            'image_url' => 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?auto=format&fit=crop&w=600&h=320&q=80',
                        ],
                        [
                            'title' => 'Central Executive Committee (CEC)',
                            'subtitle' => 'Providing strategic insight and domain expertise',
                            'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&h=320&q=80',
                        ],
                        [
                            'title' => 'Executive Committee (EXCO)',
                            'subtitle' => 'Driving programmes, coordination, and implementation',
                            'image_url' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=600&h=320&q=80',
                        ],
                    ],
                    'quote' => 'Driven by purpose. United in impact.',
                    'ctas' => [
                        ['label' => 'Connect With Our Team', 'url' => '/contact-us', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'our-ecosystem-lead'],
            [
                'title' => 'Ecosystem overview',
                'zone' => 'cms',
                'cms_page_id' => $pid('our-ecosystem'),
                'content' => '',
                'settings' => [
                    'cms_layout' => 'lead',
                    'headline' => 'One Ecosystem. Three Engines of Impact.',
                    'subheadline' => 'A structured and integrated approach that transforms ideas into action through three complementary roles.',
                    'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1800&h=800&q=80',
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'our-ecosystem-pillars'],
            [
                'title' => 'Shape, Connect, Deliver',
                'zone' => 'cms',
                'cms_page_id' => $pid('our-ecosystem'),
                'content' => '',
                'settings' => [
                    'cms_layout' => 'pillars_trio',
                    'pillars' => [
                        [
                            'accent' => 'green',
                            'tagline' => 'Shape — FIKRAH (Strategic Think Tank)',
                            'title' => 'FIKRAH',
                            'body' => 'We shape direction through policy thinking, research, and long-term strategy — guiding how communities, institutions, and partners move forward with clarity and purpose.',
                            'more' => '<p>FIKRAH serves as the strategic think tank, driving research, policy development, and thought leadership to shape future-ready frameworks. Through actionable insights and long-term strategy, it defines direction and provides the blueprint that guides programmes, partnerships, and national impact with clarity and purpose.</p>',
                            'link_url' => 'https://www.fikrah.org',
                            'link_label' => 'More info at fikrah.org',
                            'new_tab' => true,
                        ],
                        [
                            'accent' => 'blue',
                            'tagline' => 'Connect — Pertubuhan Gabungan MUKMIN Nasional',
                            'title' => 'Gabungan MUKMIN Nasional',
                            'body' => 'We convene stakeholders, align organisations, and coordinate action at scale — turning individual efforts into a collective national movement.',
                            'more' => '<p>Pertubuhan Gabungan MUKMIN Nasional is the national coordinating platform that convenes NGOs, civil society organisations (CSOs), institutions, and community networks across Malaysia. It aligns stakeholders, mobilises collaboration, and coordinates action at scale — transforming individual efforts into a unified, collective movement that connects ideas to partnerships and impact.</p>',
                            'link_url' => '',
                            'link_label' => '',
                            'new_tab' => false,
                        ],
                        [
                            'accent' => 'orange',
                            'tagline' => 'Deliver — Yayasan MUKMIN',
                            'title' => 'Yayasan MUKMIN',
                            'body' => 'We implement programmes on the ground, translating ideas into measurable outcomes through education access, convening platforms, and community-based initiatives.',
                            'more' => '<p>Yayasan MUKMIN is the implementation arm that translates strategy into measurable outcomes on the ground. Through programmes focused on education, skills development, and socio-economic empowerment, it delivers impactful initiatives that uplift communities and drive sustainable, long-term change.</p>',
                            'link_url' => '',
                            'link_label' => '',
                            'new_tab' => false,
                        ],
                    ],
                ],
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'our-ecosystem-partner-cta'],
            [
                'title' => 'Partner With Us',
                'zone' => 'cms',
                'cms_page_id' => $pid('our-ecosystem'),
                'content' => '<p>Ready to explore partnership pathways, joint programmes, or national coordination? Our partnerships desk will help you take the next step.</p>',
                'settings' => [
                    'cms_layout' => 'lead',
                    'variant' => 'gradient',
                    'suppress_title' => true,
                    'headline' => 'Partner With Us',
                    'subheadline' => 'Connect your organisation to a coordinated national movement for inclusive development.',
                    'ctas' => [
                        ['label' => 'Partnership opportunities', 'url' => '/page/cta-partners', 'style' => 'primary', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 20,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'impact-areas-intro'],
            [
                'title' => 'Strategic initiatives overview',
                'zone' => 'cms',
                'cms_page_id' => $pid('impact-areas'),
                'content' => <<<'HTML'
<p>Our five strategic impact areas define a clear and coordinated pathway to advance inclusive and sustainable development — integrating community empowerment, talent development, leadership, innovation, and values-based cohesion.</p>
<p>Together, these initiatives form a scalable, future-ready framework that connects local impact with regional and global opportunities — driving long-term transformation and collective prosperity.</p>
HTML
                ,
                'settings' => [
                    'cms_layout' => 'impact_intro',
                    'eyebrow' => 'Driving Impact',
                    'headline' => 'Strategic Initiatives (2026–2030)',
                    'subheadline' => 'A nationally anchored, globally connected framework driving inclusive growth, talent development, leadership, innovation, and community cohesion.',
                    'image_url' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1800&h=800&q=80',
                    'pathway' => 'Empower → Develop → Lead → Innovate → Unite',
                    'support_line' => 'A continuous pathway that equips communities not just to participate, but to thrive — translating vision into action and collaboration into lasting impact.',
                    'ctas' => [
                        ['label' => 'Explore our strategic initiatives', 'url' => '/page/impact-areas#pillars', 'style' => 'primary', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'impact-areas-pillars'],
            [
                'title' => 'Five strategic impact areas',
                'zone' => 'cms',
                'cms_page_id' => $pid('impact-areas'),
                'content' => '',
                'settings' => [
                    'cms_layout' => 'impact_pillars',
                    'anchor_id' => 'pillars',
                    'pillars' => [
                        [
                            'accent' => 'green',
                            'label' => '1. Socio-Economic Mobility',
                            'title' => 'From assistance to sustainable participation',
                            'body' => 'We enable communities to transition from assistance to sustainable economic participation — unlocking pathways for income growth, enterprise development, and regional opportunities.',
                            'image_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&h=400&q=80',
                            'bullets' => [
                                'Community enterprise development with regional and cross-border potential',
                                'Empowering women and youth as drivers of inclusive growth',
                                'Strengthening pathways towards long-term financial resilience',
                            ],
                        ],
                        [
                            'accent' => 'blue',
                            'label' => '2. Education & Talent Pipeline',
                            'title' => 'Future-ready talent pathways',
                            'body' => 'We build a future-ready talent pipeline through integrated academic and skills-based pathways aligned to industry and global relevance.',
                            'image_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&h=400&q=80',
                            'bullets' => [
                                'Flagship: MUKMIN Future Leaders Scholarship (MFLS)',
                                'RM20–30 million cumulative scholarship and talent development pool (2026–2030)',
                                'Dual pathway model: TVET, academic (Foundation–Master), and industry certifications',
                                'Structured mentorship, leadership development, and global exposure opportunities',
                                'Development of values-driven, globally competitive talent',
                            ],
                        ],
                        [
                            'accent' => 'orange',
                            'label' => '3. Leadership & Capacity Building',
                            'title' => 'Leaders grounded in values',
                            'body' => 'We develop leaders equipped to operate at both national and global levels — grounded in values and driven by impact.',
                            'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&h=400&q=80',
                            'bullets' => [
                                'Flagship platform: SIRAT Series',
                                'SIRAT Youth Summit → talent development and youth mobilisation',
                                'SIRAT Leaders Dialogue → policy dialogue and leadership alignment',
                                'SIRAT Global Forum → diaspora engagement and international collaboration',
                            ],
                        ],
                        [
                            'accent' => 'purple',
                            'label' => '4. Entrepreneurship & Innovation',
                            'title' => 'Participation in the digital and global economy',
                            'body' => 'We empower participation in the digital, innovation, and global economy by strengthening capabilities and enabling scalable growth.',
                            'image_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&h=400&q=80',
                            'bullets' => [
                                'Strengthening SMEs and micro-entrepreneurs for regional expansion',
                                'Expanding participation in the digital and platform economy',
                                'Building innovation ecosystems through cross-border collaboration',
                            ],
                        ],
                        [
                            'accent' => 'yellow',
                            'label' => '5. Faith, Identity & Social Cohesion',
                            'title' => 'Values, identity, and unity',
                            'body' => 'We anchor development in values, identity, and unity — strengthening communities in an increasingly interconnected world.',
                            'image_url' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=800&h=400&q=80',
                            'bullets' => [
                                'Scalable platforms such as Digital Madrasah for continuous learning',
                                'Preservation of heritage and identity within a global context',
                                'Strengthening social cohesion, unity, and shared purpose across communities',
                            ],
                        ],
                    ],
                ],
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-initiatives-lead'],
            [
                'title' => 'Featured Initiatives',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '',
                'settings' => [
                    'cms_layout' => 'lead',
                    'eyebrow' => 'Featured Initiatives',
                    'headline' => 'Turning Strategy Into Action',
                    'subheadline' => 'Flagship programmes that translate our strategic priorities into real opportunities, measurable outcomes, and lasting impact.',
                    'image_url' => 'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?auto=format&fit=crop&w=1800&h=800&q=80',
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-mfls-spotlight'],
            [
                'title' => 'MUKMIN Future Leaders Scholarship (MFLS)',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '<p>A national scholarship and talent development programme designed to build a future-ready generation through integrated academic and skills-based pathways — connecting education, leadership development, and long-term community impact.</p>',
                'settings' => [
                    'cms_layout' => 'initiative_spotlight',
                    'tagline' => 'Two Pathways. One Future.',
                    'ctas' => [
                        ['label' => 'Explore MFLS', 'url' => '#mfls-detail', 'style' => 'primary', 'new_tab' => false],
                        ['label' => 'Apply Now', 'url' => '/apply-scholarship', 'style' => 'ghost', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-mfls-detail'],
            [
                'title' => 'About MFLS',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '<p id="mfls-detail"><strong>About MFLS.</strong> MFLS serves as a structured talent pipeline that connects access to education with leadership development and long-term community impact. Delivered in collaboration with leading universities and institutions, it creates multiple pathways for students to succeed and contribute meaningfully to society.</p>'.$mflsDetail,
                'settings' => [
                    'cms_layout' => 'html',
                    'suppress_title' => false,
                ],
                'sort_order' => 20,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-sirat'],
            [
                'title' => 'SIRAT Series',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => <<<'HTML'
<p>The SIRAT Series is MUKMIN&rsquo;s flagship capacity-building platform, designed to develop leadership, strengthen networks, and catalyse collaboration across national, regional, and global levels.</p>
<p>Bringing together youth, leaders, and diaspora communities, it creates a dynamic ecosystem for dialogue, knowledge exchange, and collective action — driving both community impact and cross-border collaboration.</p>
HTML
                ,
                'settings' => [
                    'cms_layout' => 'initiative_article',
                    'eyebrow' => 'SIRAT Series',
                    'headline' => 'Developing Leaders. Connecting Networks. Driving Impact.',
                    'note' => 'Information on the 2026/2027 SIRAT Series will be announced soon.',
                    'cta_label' => 'Learn more',
                    'cta_url' => '/page/impact-areas',
                    'cta_new_tab' => false,
                ],
                'sort_order' => 30,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-fikrah'],
            [
                'title' => 'FIKRAH Blueprint & KL Declaration',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => <<<'HTML'
<p>Anchored in long-term, sustainable development, the FIKRAH Blueprint provides a strategic framework to align community-driven initiatives with national priorities.</p>
<p>Complementing this, the KL Declaration — launched at the Global SIRAT Forum — serves as a multi-stakeholder commitment platform, bringing together leaders across government, industry, academia, and civil society to drive coordinated, collective action.</p>
<p>Together, they position FIKRAH as a thought leadership and policy catalyst — translating dialogue into actionable pathways that advance inclusive community development.</p>
HTML
                ,
                'settings' => [
                    'cms_layout' => 'initiative_article',
                    'eyebrow' => 'FIKRAH Blueprint & KL Declaration',
                    'headline' => 'Shaping Direction. Aligning Action. Enabling Impact.',
                    'cta_label' => 'Learn more at fikrah.org',
                    'cta_url' => 'https://www.fikrah.org',
                    'cta_new_tab' => true,
                ],
                'sort_order' => 40,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-madrasah'],
            [
                'title' => 'Digital Madrasah',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => <<<'HTML'
<p>Digital Madrasah is a strategic initiative advancing faith-based learning through digital innovation at scale — making Islamic education more accessible, relevant, and engaging for the next generation.</p>
<p>Aligned with Malaysia&rsquo;s digital agenda, it integrates technology-enabled learning, interactive platforms, and digital content to bridge values-based education with future-ready capabilities.</p>
<ul>
<li>Scalable and continuous faith-based learning</li>
<li>Youth engagement through digital platforms</li>
<li>Integration of digital literacy, creativity, and innovation</li>
<li>Development of values-driven, digitally capable individuals</li>
</ul>
HTML
                ,
                'settings' => [
                    'cms_layout' => 'initiative_article',
                    'eyebrow' => 'Digital Madrasah',
                    'headline' => 'Where Faith Meets the Digital Future',
                    'note' => 'More information will be announced soon.',
                    'cta_label' => 'Learn more',
                    'cta_url' => '/contact-us',
                    'cta_new_tab' => false,
                ],
                'sort_order' => 50,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-gallery-teaser'],
            [
                'title' => 'Gallery',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '<p>Explore highlights from our programmes, events, and engagements — reflecting the spirit of collaboration, leadership, and community transformation in action.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'headline' => 'Moments That Matter',
                    'subheadline' => 'Capturing the people, partnerships, and initiatives shaping meaningful impact across communities.',
                    'cta_label' => 'View gallery',
                    'cta_url' => '/page/featured-initiatives#gallery',
                    'anchor_id' => 'gallery',
                ],
                'sort_order' => 60,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-contact-teaser'],
            [
                'title' => 'Contact Us',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '<p>We welcome partnerships, collaborations, and enquiries from across sectors.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'headline' => 'Let\'s Connect',
                    'subheadline' => 'Whether you\'re looking to collaborate, support our initiatives, or learn more about our work, our team is here to connect and explore opportunities together.',
                    'cta_label' => 'Get in Touch',
                    'cta_url' => '/contact-us',
                ],
                'sort_order' => 70,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-register-teaser'],
            [
                'title' => 'Join the Movement',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '<p>Register your interest to participate in MUKMIN initiatives, programmes, and platforms — and be part of a collaborative ecosystem shaping inclusive and sustainable development.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'headline' => 'Be Part of the Movement',
                    'subheadline' => 'Join a growing network of organisations, partners, and individuals driving meaningful impact.',
                    'cta_label' => 'Register Now',
                    'cta_url' => '/register',
                ],
                'sort_order' => 80,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'featured-donate-teaser'],
            [
                'title' => 'Support Our Work',
                'zone' => 'cms',
                'cms_page_id' => $pid('featured-initiatives'),
                'content' => '<p>Support our programmes and initiatives by contributing to efforts that create real opportunities and long-term change across communities.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'headline' => 'Give with Purpose. Create Lasting Impact.',
                    'subheadline' => 'Your contribution helps expand access, empower communities, and sustain impactful initiatives.',
                    'cta_label' => 'Donate Now',
                    'cta_url' => '/page/cta-partners#donate',
                ],
                'sort_order' => 90,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'cta-partners-lead'],
            [
                'title' => 'Partnerships',
                'zone' => 'cms',
                'cms_page_id' => $pid('cta-partners'),
                'content' => <<<'HTML'
<p>MUKMIN works with institutions, civil society, and private-sector partners to co-design programmes, mobilise communities, and scale impact. If you are exploring collaboration, sponsorship, or joint implementation, start with a conversation — we will route you to the right workstream.</p>
HTML
                ,
                'settings' => [
                    'cms_layout' => 'lead',
                    'eyebrow' => 'CTA / Partners',
                    'headline' => 'Partner for inclusive impact',
                    'subheadline' => 'We welcome partnerships, collaborations, and enquiries from across sectors.',
                    'image_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1800&h=800&q=80',
                ],
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'cta-partners-strip'],
            [
                'title' => 'Partner With Us',
                'zone' => 'cms',
                'cms_page_id' => $pid('cta-partners'),
                'content' => '<p>Share a brief on your organisation and the partnership angle you have in mind. Our team will follow up with next steps.</p>',
                'settings' => [
                    'cms_layout' => 'lead',
                    'variant' => 'gradient',
                    'suppress_title' => true,
                    'headline' => 'Partner With Us',
                    'subheadline' => 'Connect your work to national programmes, scholarship pathways, and community platforms.',
                    'ctas' => [
                        ['label' => 'Email partnerships', 'url' => 'mailto:partners@mukmin.org', 'style' => 'primary', 'new_tab' => false],
                        ['label' => 'Explore initiatives', 'url' => '/page/featured-initiatives', 'style' => 'ghost', 'new_tab' => false],
                    ],
                ],
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'cta-partners-contact'],
            [
                'title' => 'Contact Us',
                'zone' => 'cms',
                'cms_page_id' => $pid('cta-partners'),
                'content' => '<p>Whether you\'re looking to collaborate, support our initiatives, or learn more about our work, our team is here to connect and explore opportunities together.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'anchor_id' => 'contact',
                    'headline' => 'Let\'s Connect',
                    'subheadline' => 'General enquiries and introductions.',
                    'cta_label' => 'Get in Touch',
                    'cta_url' => '/contact-us',
                ],
                'sort_order' => 20,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'cta-partners-register'],
            [
                'title' => 'Join the Movement',
                'zone' => 'cms',
                'cms_page_id' => $pid('cta-partners'),
                'content' => '<p>Join a growing network of organisations, partners, and individuals driving meaningful impact.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'anchor_id' => 'register',
                    'headline' => 'Be Part of the Movement',
                    'subheadline' => 'Join a growing network of organisations, partners, and individuals driving meaningful impact.',
                    'cta_label' => 'Register Now',
                    'cta_url' => '/register',
                ],
                'sort_order' => 30,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'cta-partners-donate'],
            [
                'title' => 'Support Our Work',
                'zone' => 'cms',
                'cms_page_id' => $pid('cta-partners'),
                'content' => '<p>Your contribution helps expand access, empower communities, and sustain impactful initiatives.</p>',
                'settings' => [
                    'cms_layout' => 'teaser_card',
                    'anchor_id' => 'donate',
                    'headline' => 'Give with Purpose. Create Lasting Impact.',
                    'subheadline' => 'Replace this mailto link with your preferred donation gateway when available.',
                    'cta_label' => 'Donate Now',
                    'cta_url' => 'mailto:donate@mukmin.org',
                ],
                'sort_order' => 40,
                'is_active' => true,
            ]
        );
    }
}
