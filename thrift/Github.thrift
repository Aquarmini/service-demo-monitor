namespace php GithubService

struct UserProfile {
    1: i32 uid = 1,
    2: string username = "User1",
    3: string commits
}

service Github {
    bool receivedEvents(string token)
    bool commits(string committer, string token)

}