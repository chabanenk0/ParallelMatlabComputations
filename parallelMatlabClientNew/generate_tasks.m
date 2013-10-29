function generate_tasks(infilename,measurefunction,wind,methodid,tbeg,tend,tstep);
% ������� ��� ��������� ������ ������� ��� ��������� ���������� ����
% ������� ���������:
% infilename - ��� �������� �����, ������� ����� ���������������
% wind - ���� ��������
% nfragments - ���-�� ����������, �� ������� ����� ������� ������� (��
%              ��������� - 20
% methodid - �� ������ ������� �������� (c ����������� ������� �������� ���������� ����)
% tbeg,tend - ������ � ����� ��������� ��������� ����� ����, ��������
%             ������� ����� ����������������. �� ��������� - wind+1 � n
% measurefunction - ����� ������-������� ��� ������ ������� �������� ����
%                   ����������� ���������� y_fragment ��� ���������� ���� �
%                   ���������� ��������� ��� ������� ���������� ��������.
if (nargin<5)
    tbeg=wind+1;
end
y=dlmread(infilename);
n=length(y);
if (nargin<6)
    tend=n;
end
if (nargin<7)
    tstep=1;
end


userid=1;
descr='CalcInWindow';
resid=1;% id ��������������� ����!!! ����� �������� ��������� ��� ��� �������� �������
server='http://127.0.0.1:80/';
%server='http://prognoz.ck.ua:80/';

% ��� ���������� ������ ����:
%/TS/resultseries.php?action=new
url=strcat(server,['TS/resultseries.php?action=new']);
% ��������� lastdate, lasttime, name, type
params = {'lastdate','0000-00-00', 'lasttime','00:00:00','name','newseries',...
    'type',num2str(1),'doitdas4dsa454a6s54da65s4a6s5d4a6s5','1'}; 
data=urlread(url,'POST',params);
[resid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
data=data(NEXTINDEX:length(data));

dataid=upload_data(infilename,'File_ForCalculation.rar',userid,descr,server);
fp=fopen('commands.m','w');
for i=tbeg:tstep:tend
    fprintf(fp,'calculate_task(%c%s%c,%c%s%c,%d,%d,%d)\n',39,measurefunction,39,39,infilename,39,wind,i,resid);
end
fclose(fp);

predictminutes=60;
taskgroupdata=0;
[taskid,taskgroupid]=add_tasks_file('commands.m',methodid,dataid,userid,predictminutes,taskgroupdata,server);
taskgroupdata2=taskgroupid;
%add_task(command_i,methodid,dataid,userid,predictminutes,taskgroupdata2,server);
