namespace php Xin.Thrift.MonitorService

include "github/commits.thrift"
include "baidu/tieba.thrift"

service Github {
    // 触发消息队列 收到的公共事件订阅信息
    bool receivedEvents(string username, string token)

    // 触发消息队列 查看当前的commits数
    bool commits(string committer, string token)

    // 显示时间段内，commits变化
    list<commits.CommitsLog> commitsLog(string committer, i32 btime, i32 etime)
}

service Baidu {
    // 贴吧签到
    bool tiebaSign(string bdUss, string nickName)

    // 获取我的贴吧列表
    list<tieba.BaiduTieba> myTiebas(string bdUss, string nickName)

    // 获取我的某个贴吧
    tieba.BaiduTieba tieba(string bdUss, string nickName, string name)
}