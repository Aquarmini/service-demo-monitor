namespace php GithubService

service Github {
    bool receivedEvents(string token)
    bool commits(string committer, string token)
}