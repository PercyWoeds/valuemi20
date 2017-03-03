class AddNotumIdToNotacredit < ActiveRecord::Migration
  def change
    add_column :notacredits, :notum_id, :integer
  end
end
