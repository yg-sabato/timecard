<script setup>
    import { defineProps, ref } from 'vue';
    const props = defineProps({
        updateTmpDescription: Function,
    });
    const inputUrl = ref('');
    const isLoading = ref(false);
    const githubData = ref({});

    props.updateTmpDescription('test');

    const fetchGithubData = async () => {
        isLoading.value = true;

        const token = import.meta.env.VITE_GITHUB_ACCESS_TOKEN;
        const headers = {
            'Authorization': `token ${token}`
        };

        // URLから必要な情報を抽出
        const regex = /github\.com\/(.+)\/(.+)\/(issues|pull)\/(\d+)/;
        const match = inputUrl.value.match(regex);

        if (!match) {
            isLoading.value = false;
            throw new Error('Invalid GitHub URL');
        }

        // 抽出した情報を変数に代入
        const [, owner, repo, type, number] = match;
        
        // IssueまたはPull Requestのエンドポイントを構築
        const apiURL = `https://api.github.com/repos/${owner}/${repo}/${type === 'issues' ? 'issues' : 'pulls'}/${number}`;

        // APIリクエストを送信
        const response = await fetch(apiURL, { headers });
        if (!response.ok) {
            isLoading.value = false;
            throw new Error('Failed to fetch GitHub data');
        }

        // レスポンスデータをJSON形式で取得
        const data = await response.json();

        // リポジトリ名を取得
        const repoName = data.repository_url.split('/').pop();

        // 必要な情報を抽出
        githubData.value = {
            title: data.title,
            body: data.body,
            url: data.html_url,
            repoName: repoName,
        };

        isLoading.value = false;

        // 入力欄をクリア
        inputUrl.value = '';

        // 記録の入力を追加
        props.updateTmpDescription(`${githubData.repoName}: ${githubData.title}`);
    };

</script>

<template>
    <h2><i class="fa-brands fa-github"></i> Github連携</h2>
    <VaProgressBar v-show="isLoading" indeterminate />
    <VaInput
        label="issueかpull requestのURLを入力"
        v-model="inputUrl"
    ></VaInput>
    <VaButton @click="fetchGithubData">データを取得</VaButton>
    <div class="cards">
        <a :href="githubData['url']" target="_blank" rel="noopener noreferrer">
            <VaCard>
                <VaCardTitle><i class="fa-brands fa-github"></i> {{ githubData['repoName'] }}</VaCardTitle>
                <VaCardContent>
                    <h3>{{ githubData['title'] }}</h3>
                    <p>{{ githubData['body'] }}</p>
                </VaCardContent>
            </VaCard>
        </a>
    </div>
</template>

<style lang="scss">
    .va-input {
        width: 100%;
    }
    .va-button {
        max-width: 300px;
        width: 100%;
    }
    .cards {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .va-card {
        width: 100%;
        max-width: 400px;
    }
</style>