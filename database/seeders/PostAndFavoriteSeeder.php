<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Favorite;
use Carbon\Carbon;

class PostAndFavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
    [
        'title' => '毎朝のブラウザ起動と業務サイト自動ログイン',
        'body'  => '業務開始時に必ず利用するブラウザを自動で起動し、指定された業務ポータルサイトを開きます。これにより出社直後にパスワード入力やブックマーク検索に時間をかけずに済み、作業の立ち上がりがスムーズになります。習慣化している準備作業をタスク化することで、朝のルーチンを省略でき、業務効率の改善に直結します。',
        'action_type'  => 'program',
        'program_path' => 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'arguments'    => 'https://intranet.example.com',
        'screenshot_path' => 'screenshots/task01.webp',
    ],
    [
        'title' => '定例会議開始15分前のリマインド通知',
        'body'  => '毎週行われる定例会議の開始15分前にポップアップ通知を表示します。会議室の場所やオンライン会議の接続URLを事前に確認するきっかけとなり、資料の準備や移動時間の確保を忘れずに行えます。多忙な日常では会議の予定を見落とすこともあるため、こうしたリマインドがあることで、全員がスムーズに参加でき、進行に遅延が発生しにくくなります。',
        'action_type'   => 'popup',
        'popup_title'   => '会議リマインド',
        'popup_message' => '定例会議が15分後に始まります。会議室やURLを確認してください。',
        'screenshot_path' => 'screenshots/task02.webp',
    ],
    [
        'title' => '業務終了時のバックアップスクリプト実行',
        'body'  => '一日の業務終了時刻に合わせて、自動的にバックアップスクリプトを実行し、ドキュメントフォルダを外部ストレージへコピーします。毎日繰り返される作業を手動で行うと忘れがちですが、自動化により確実性を高められます。突発的なPCトラブルやファイル破損にも備えられ、復旧にかかる時間と労力を削減できます。データの安全性を確保することで、安心して業務を終えられます。',
        'action_type'  => 'program',
        'program_path' => 'C:\\scripts\\backup.bat',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task03.jpeg',
    ],
    [
        'title' => '月次報告書作成のリマインド通知',
        'body'  => '毎月末の午後に、報告書作成を促す通知を表示します。日常業務に追われて締切直前まで着手できないことを防ぎ、余裕を持って内容を整理できるようにします。特に月末は経理処理や各種提出物が重なるため、事前に意識を向けることが重要です。この通知により作業計画を立て直すきっかけとなり、報告書の品質向上と業務フローの安定化につながります。',
        'action_type'   => 'popup',
        'popup_title'   => '月次報告書作成',
        'popup_message' => '本日中に月次報告書の作成をお願いします。',
        'screenshot_path' => 'screenshots/task04.png',
    ],
    [
        'title' => '昼休憩終了10分前の通知',
        'body'  => '昼休憩の終了10分前にポップアップ通知を表示し、業務に戻る準備を促します。長めの昼休憩後は切り替えが遅れて会議や業務に支障が出ることがありますが、この通知によりスムーズに復帰できます。社員が時間を意識して行動できるようにすることで、午後の会議や打ち合わせ開始に遅れず参加でき、全体の進行に影響を与えません。時間管理を支援する小さな工夫です。',
        'action_type'   => 'popup',
        'popup_title'   => '休憩終了リマインド',
        'popup_message' => '昼休憩がまもなく終了します。そろそろ準備を始めましょう。',
        'screenshot_path' => 'screenshots/task05.png',
    ],
    [
        'title' => '社内チャットツールの自動起動',
        'body'  => '業務開始と同時に社内チャットツールを起動し、チームとの連絡がすぐに取れる状態を作ります。朝のうちにステータスをオンラインにすることで、緊急の連絡や作業依頼を見逃さずに対応可能です。毎日手動で立ち上げる手間をなくし、準備作業の一環として自動化することで、無駄のない一日のスタートを切れます。コミュニケーションの活性化にもつながります。',
        'action_type'  => 'program',
        'program_path' => 'C:\\Program Files\\Teams\\current\\Teams.exe',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task06.jpg',
    ],
    [
        'title' => '請求書処理締切前日の通知',
        'body'  => '請求書の処理締切前日に通知を出し、業務担当者に早めの着手を促します。経理業務は月末に集中しがちで、対応が遅れると社内外の調整に影響を及ぼす恐れがあります。前日から意識することで作業に余裕を持て、誤記や抜け漏れも減少します。スムーズな決裁フローを実現するためのシンプルながら効果的な仕組みです。',
        'action_type'   => 'popup',
        'popup_title'   => '請求書処理リマインド',
        'popup_message' => '明日が請求書処理の締切日です。忘れずに対応してください。',
        'screenshot_path' => 'screenshots/task07.webp',
    ],
    [
        'title' => '定時終了後のPC自動シャットダウン',
        'body'  => '勤務時間終了後、指定した時刻にPCを自動シャットダウンさせます。長時間の電源入れっぱなしを防ぎ、セキュリティ強化や電力消費削減につながります。さらに残業を抑制する効果もあり、社員の働き方改革をサポートします。定時に区切りをつける習慣を自然に作るための補助的なタスクです。',
        'action_type'  => 'program',
        'program_path' => 'C:\\Windows\\System32\\shutdown.exe',
        'arguments'    => '/s /f /t 0',
        'screenshot_path' => 'screenshots/task08.webp',
    ],
    [
        'title' => '週次データ集計スクリプト実行',
        'body'  => '毎週金曜の夕方に、売上データや利用状況をまとめるスクリプトを自動実行します。担当者は出力結果を確認するだけで済み、作業効率が向上します。定期的な処理を人手で行う必要がなくなり、工数削減と精度向上を同時に実現します。定例業務を安定して遂行できるため、チーム全体の信頼性も高まります。',
        'action_type'  => 'program',
        'program_path' => 'C:\\scripts\\weekly_report.ps1',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task09.jpg',
    ],
    [
        'title' => 'セキュリティパッチ更新の通知',
        'body'  => 'OSや業務アプリの更新が必要な日付に、パッチ適用を促す通知を表示します。定期的な更新はセキュリティリスクを抑えるために不可欠ですが、日常業務の中で後回しにされがちです。通知により計画的な対応を促し、脆弱性を突かれるリスクを最小限に抑えます。IT部門からの依頼を確実に実行できるよう支援します。',
        'action_type'   => 'popup',
        'popup_title'   => 'セキュリティ更新通知',
        'popup_message' => '本日はセキュリティパッチ更新日です。速やかに適用してください。',
        'screenshot_path' => 'screenshots/task10.png',
    ],
    [
        'title' => '朝会開始5分前の通知',
        'body'  => '毎日朝の定例朝会の直前に通知を表示し、資料やメモを準備できるようにします。短時間で重要な共有を行う朝会では、開始の遅れが全体のスケジュールに影響します。このリマインドにより全員が時間を意識し、効率的に集まることが可能です。結果としてチームの生産性が高まり、1日の良いスタートを切れます。',
        'action_type'   => 'popup',
        'popup_title'   => '朝会リマインド',
        'popup_message' => '朝会が5分後に始まります。会議室またはオンラインURLを確認しましょう。',
        'screenshot_path' => 'screenshots/task11.jpg',
    ],
    [
        'title' => '自動ログ収集バッチ実行',
        'body'  => 'システム稼働状況を毎日深夜に記録するためのログ収集バッチを起動します。人手をかけずに必要な情報を自動で保存することで、障害発生時の調査に役立ちます。日々の運用を効率化し、トラブルシューティングにかかる時間を大幅に短縮できます。システム運用の信頼性向上を目的としています。',
        'action_type'  => 'program',
        'program_path' => 'C:\\scripts\\collect_logs.bat',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task12.png',
    ],
    [
        'title' => '週末前のデータ入力最終確認通知',
        'body'  => '金曜夕方に、週内に入力漏れがないか確認を促す通知を表示します。週明けに修正するよりも効率的で、翌週の業務に影響を及ぼしません。特に営業活動や勤怠関連の入力忘れを防ぐ効果があり、組織全体のデータ品質を高めます。ミスや漏れのない状態で週を終えることができます。',
        'action_type'   => 'popup',
        'popup_title'   => '入力確認リマインド',
        'popup_message' => '今週のデータ入力に漏れがないか確認してください。',
        'screenshot_path' => 'screenshots/task13.webp',
    ],
    [
        'title' => '定時のチャット自動送信スクリプト実行',
        'body'  => '毎日定時に「退勤しました」と自動送信するスクリプトを起動し、勤怠管理をスムーズにします。記録忘れを防ぎ、管理部門が正確な情報を把握できます。社員が余計な手間をかけずに済むため、心理的負担の軽減にもなります。ルーチンワークをシステム化することで安定運用を実現します。',
        'action_type'  => 'program',
        'program_path' => 'C:\\scripts\\auto_chat.ps1',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task14.png',
    ],
    [
        'title' => '会議室予約確認リマインド',
        'body'  => '会議開始30分前に、予約している会議室が確保されているか確認を促す通知を表示します。会議室の取り違えや予約忘れによる混乱を防止します。事前にチェックする習慣を促すことで、トラブルを回避し、会議を予定通り始められるようにします。参加者全員の時間を大切にできます。',
        'action_type'   => 'popup',
        'popup_title'   => '会議室確認通知',
        'popup_message' => '30分後に会議が始まります。予約状況を確認してください。',
        'screenshot_path' => 'screenshots/task15.png',
    ],
    [
        'title' => '日報作成リマインド通知',
        'body'  => '勤務終了前に日報作成を促す通知を表示します。忘れずに日報を提出することで情報共有が円滑になり、チーム全体の透明性が高まります。日常業務の振り返りをすることで改善点も見つかりやすくなります。毎日の習慣を定着させるための補助的な仕組みです。',
        'action_type'   => 'popup',
        'popup_title'   => '日報作成リマインド',
        'popup_message' => '本日の業務終了前に日報を提出してください。',
        'screenshot_path' => 'screenshots/task16.webp',
    ],
    [
        'title' => '深夜バックアップジョブ実行',
        'body'  => '毎日深夜に重要データを別サーバへ転送するバックアップジョブを実行します。業務時間外に処理することで業務への影響を最小限に抑えられます。突発的な障害やサイバー攻撃からデータを守るため、欠かせない仕組みです。安定した業務継続を支える基盤の一つです。',
        'action_type'  => 'program',
        'program_path' => 'C:\\scripts\\nightly_backup.bat',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task17.jpg',
    ],
    [
        'title' => '月初の勤怠入力確認通知',
        'body'  => '毎月1日の朝、前月の勤怠入力が完了しているかを確認する通知を表示します。締切直前の慌ただしい修正を避け、正確な勤怠管理を実現します。余裕を持った対応により、管理部門の作業負荷も軽減できます。月初のルーチンとして定着させることで、勤怠管理の精度が高まります。',
        'action_type'   => 'popup',
        'popup_title'   => '勤怠入力確認',
        'popup_message' => '先月分の勤怠入力を確認してください。',
        'screenshot_path' => 'screenshots/task18.png',
    ],
    [
        'title' => '定例メンテナンススクリプト起動',
        'body'  => '毎月第2日曜日深夜に、システムメンテナンススクリプトを自動起動します。不要なファイル削除や最適化処理を行い、システムを健全に保ちます。定期的なメンテナンスを確実に実行することで、安定稼働と障害防止につながります。手作業の負担を減らすことにも貢献します。',
        'action_type'  => 'program',
        'program_path' => 'C:\\scripts\\maintenance.ps1',
        'arguments'    => '',
        'screenshot_path' => 'screenshots/task19.webp',
    ],
    [
        'title' => '年末業務締め作業リマインド通知',
        'body'  => '年末の最終出勤日に、業務締めや各種提出物の準備を促す通知を表示します。提出物忘れや処理漏れを防ぎ、新年を安心して迎えることができます。慌ただしい時期だからこそ、自動でリマインドを受けられることで精神的な余裕も生まれます。全員で業務をきれいに締めくくるための重要な仕組みです。',
        'action_type'   => 'popup',
        'popup_title'   => '年末業務締めリマインド',
        'popup_message' => '年末の業務締めを行ってください。提出物の確認も忘れずに。',
        'screenshot_path' => 'screenshots/task20.png',
    ],
];

        $start = Carbon::create(2025, 9, 25, 0, 0, 0);
        $end   = Carbon::create(2025, 10, 3, 23, 59, 59);

        $users = User::all();

        // ★ 各タスクの使用回数を記録する配列
        $titleCounters = [];

        foreach ($users as $user) {
            $postCount = rand(1, 5);
            $selectedTasks = collect($tasks)->random($postCount);

            foreach ($selectedTasks as $task) {
                // タイトル使用回数をカウントアップ
                $title = $task['title'];
                if (!isset($titleCounters[$title])) {
                    $titleCounters[$title] = 1;
                } else {
                    $titleCounters[$title]++;
                }

                // 連番付きのタイトルを生成（ゼロ埋め2桁）
                $uniqueTitle = $title . sprintf(" %02d", $titleCounters[$title]);

                Post::create([
                    'user_id'        => $user->id,
                    'title'          => $uniqueTitle,
                    'body'           => $task['body'],
                    'program_path'   => $task['program_path'] ?? null,
                    'arguments'      => $task['arguments'] ?? null,
                    'run_datetime'   => Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp)),
                    'enabled'        => true,
                    'screenshot_path'=> $task['screenshot_path'] ?? null,
                    'action_type'    => $task['action_type'],
                    'popup_title'    => $task['popup_title'] ?? null,
                    'popup_message'  => $task['popup_message'] ?? null,
                    'ps1_path'       => null,
                ]);
            }
        }

        // お気に入り処理はそのまま
        $allPosts = Post::all();
        foreach ($users as $user) {
            $favorites = $allPosts->where('user_id', '!=', $user->id);
            $favoriteCount = rand(10, 20);
            if ($favorites->count() > 0) {
                $selected = $favorites->random(min($favoriteCount, $favorites->count()));
                foreach ($selected as $post) {
                    Favorite::firstOrCreate([
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                    ]);
                }
            }
        }
    }
}
